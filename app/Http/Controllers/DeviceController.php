<?php

namespace App\Http\Controllers;

use App\Support\DeviceGate;
use App\User;
use App\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Device restriction: the agent's waiting page and the admin's approve / reject / revoke actions.
 * The rules themselves live in App\Support\DeviceGate.
 */
class DeviceController extends Controller
{
    /** Shown to an agent whose device is waiting for (or was refused) approval. */
    public function pending(Request $request)
    {
        $user = Auth::user();
        if (! DeviceGate::applies($user)) {
            return redirect('/dashboard');
        }

        $device = DeviceGate::resolve($user, $request);
        if (DeviceGate::allows($user, $device)) {
            return redirect('/dashboard');
        }

        return view('main.device.pending', ['device' => $device, 'user' => $user]);
    }

    /** Polled by the waiting page every 15 seconds. */
    public function status(Request $request)
    {
        $user = Auth::user();
        if (! DeviceGate::applies($user)) {
            return response()->json(['allowed' => true, 'status' => 'approved']);
        }

        $device = DeviceGate::resolve($user, $request);

        return response()->json(['allowed' => DeviceGate::allows($user, $device), 'status' => $device->status]);
    }

    /** Admins and Managers manage devices; only an Admin may touch an Admin's. */
    private function manageable(UserDevice $device): UserDevice
    {
        $actor = Auth::user();
        abort_unless($actor && in_array((int) $actor->role, [1, 9], true), 403);
        abort_if((int) optional($device->user)->role === 1 && (int) $actor->role !== 1, 403);

        return $device;
    }

    public function approve(Request $request, UserDevice $device)
    {
        DeviceGate::approve($this->manageable($device), Auth::user(), $request->boolean('replace'));

        return back()->with('msg', 'Device approved: ' . $device->name() . ' (' . $device->code() . ').');
    }

    public function reject(UserDevice $device)
    {
        $this->manageable($device)->forceFill(['status' => UserDevice::REJECTED, 'revoked_by' => Auth::id(), 'revoked_at' => now()])->save();

        return back()->with('msg', 'Device rejected: ' . $device->name() . ' (' . $device->code() . ').');
    }

    public function revoke(UserDevice $device)
    {
        $this->manageable($device)->forceFill(['status' => UserDevice::REVOKED, 'revoked_by' => Auth::id(), 'revoked_at' => now()])->save();

        return back()->with('msg', 'Device revoked: ' . $device->name() . ' (' . $device->code() . '). It is stopped on its next page load.');
    }

    /** Every device waiting for approval, newest first. */
    public function approvals()
    {
        abort_unless(in_array((int) Auth::user()->role, [1, 9], true), 403);
        abort_unless(DeviceGate::ready(), 404);

        $devices = UserDevice::with('user')->where('status', UserDevice::PENDING)
            ->whereHas('user', fn ($q) => $q->where('deleted', 0))
            ->orderByDesc('id')->paginate(50);

        return view('main.device.approvals', compact('devices'));
    }
}
