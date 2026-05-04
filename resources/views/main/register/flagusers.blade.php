@extends('layouts.innerpages')

@section('template_title')
    Flag Users
@endsection

@section('content')
    @include('partials.mainsite_pages.return_function')

    <div class="row">
        <div class="col-12">
            <div class="card mt-5">
                <div class="card-header">
                    <div class="card-title">Flag Users</div>

                    {{-- <button id="audio-btn">Start Audio</button> --}}
                </div>
                <div class="card-body">
                    <div id="custom-chat-data">
                        <div id="Flag" class="tabcontent">
                            <table class="table table-bordered table-striped key-buttons">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0">S/No.</th>
                                        <th class="border-bottom-0">Name</th>
                                        <th class="border-bottom-0">Message</th>
                                        <th class="border-bottom-0">Reasons</th>
                                        <th class="border-bottom-0">Created Date</th>
                                        {{-- <th class="border-bottom-0">Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($flag as $key => $val)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                @if ($val->user)
                                                    {{ $val->user->name }}
                                                @else
                                                    UserName
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($val->customChat))
                                                    {{ $val->customChat->description }}
                                                @elseif (isset($val->groupChat))
                                                    {{ $val->groupChat->message }}
                                                @else
                                                    No message
                                                @endif
                                            </td>
                                            <td>{{ $val->reason ?? 'No reason' }}</td>
                                            <td>{{ $val->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-between">
                                <span class="my-auto">Showing {{ $flag->firstItem() }} to
                                    {{ $flag->lastItem() }} of {{ count($flag) }} entries from
                                    total {{ $flag->total() }}</span>
                                {{ $flag->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('extraScript')
        {{-- <script>
            let audioContext;
            let isAudioContextReady = false;

            document.getElementById('audio-btn').addEventListener('click', function() {
                if (!audioContext) {
                    audioContext = new(window.AudioContext || window.webkitAudioContext)();
                }

                if (audioContext.state === 'suspended') {
                    audioContext.resume().then(() => {
                        isAudioContextReady = true;
                    }).catch((err) => {
                        console.error('Error resuming AudioContext:', err);
                    });
                } else {
                    isAudioContextReady = true;
                }
            });

            function getChat() {
                $.ajax({
                    url: "/flag-chat2",
                    type: "GET",
                    success: function(res) {
                        $("#custom-chat-data").html(res.html);
                        if (res.new_flag) {
                            playBeep();
                        }
                    }
                });
            }

            function playBeep() {
                if (isAudioContextReady) {
                    const oscillator = audioContext.createOscillator();
                    oscillator.type = 'square';
                    oscillator.frequency.setValueAtTime(1000, audioContext.currentTime);
                    oscillator.connect(audioContext.destination);
                    oscillator.start();
                    oscillator.stop(audioContext.currentTime + 0.2);
                }
            }

            setInterval(getChat, 10000);
        </script> --}}
        <script>
            // function playBeep() {
            //     const audioContext = new(window.AudioContext || window.webkitAudioContext)();
            //     const oscillator = audioContext.createOscillator();
            //     const gainNode = audioContext.createGain();
            //     oscillator.type = 'square';
            //     oscillator.frequency.setValueAtTime(600, audioContext.currentTime);
            //     gainNode.gain.setValueAtTime(1, audioContext.currentTime);
            //     oscillator.connect(gainNode);
            //     gainNode.connect(audioContext.destination);
            //     oscillator.start();
            //     setTimeout(() => {
            //         oscillator.stop();
            //         audioContext.close();
            //     }, 300);
            // }

            // function getChat() {
            //     $.ajax({
            //         url: "/flag-chat2",
            //         type: "GET",
            //         success: function(res) {
            //             console.log('res.new_flags', res.new_flag);
            //             $("#custom-chat-data").html(res.html);
            //             if (res.new_flag) {
            //                 playBeep();
            //             }
            //         }
            //     });
            // }

            // setInterval(getChat, 10000);
        </script>
    @endsection
