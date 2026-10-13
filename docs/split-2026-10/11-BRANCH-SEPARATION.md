# Separating the Hello and CrazyRays code

**Done:** 13 October 2026 — `crazyrays` branches created and pushed in **both** repos.
**Why now:** the recruitment pipeline is Hello-only. Built on shared code it would change CrazyRays
too, or need a brand guard around every change. CrazyRays must keep working exactly as it does today.

---

## 1. The model

| Branch | Serves | Moves? |
|---|---|---|
| `main` | **hellotransport.com** + **hr.hellotransport.com** | yes — all new Hello work |
| `crazyrays` | **florida.crazyrayssolutions.com.pk** + **hr.crazyrayssolutions.com.pk** | **frozen** — fixes only |

Both repos now have both branches:

| Repo | `main` | `crazyrays` |
|---|---|---|
| `washinton_agent` | `7e41870` | `ea0b952` |
| `washinton_hr` | `18198cb` | `18198cb` |

`crazyrays` was branched from **`origin/main`**, which is what the CrazyRays portals run today — so
switching to it changes **nothing** about how they behave. That is deliberate: the branch exists to
*stop* future Hello work reaching CrazyRays, not to change CrazyRays now.

---

## 2. Switching the two CrazyRays deployments

Run on the server, in each CrazyRays folder. **Check for local changes first** — these folders have
been edited directly before, and a checkout would carry or reject them.

```bash
cd ~/florida.crazyrayssolutions.com.pk
git status                 # must be clean apart from .env / storage
git fetch origin
git checkout crazyrays
git pull
php artisan optimize:clear
```

```bash
cd ~/hr.crazyrayssolutions.com.pk
git status
git fetch origin
git checkout crazyrays
git pull
php artisan optimize:clear
```

Then toggle MultiPHP in cPanel to clear the FPM OPcache.

> **`.env` is not in git**, so the database credentials set during the split are unaffected by a
> branch switch. Verify after switching that each still points at `crazyrayssolutio_crazzy`.

### Leave the Hello deployments alone

`hellotransport.com` and `hr.hellotransport.com` stay on `main`. They need no change.

---

## 3. What this costs

A fix that applies to both brands must now be applied **twice** — once on `main`, then cherry-picked:

```bash
git checkout crazyrays
git cherry-pick <commit>
git push
```

Worth accepting deliberately. The alternative — a brand flag around every pipeline change — spreads
`if (hello)` through the HR portal and is far harder to reason about or remove later.

**Files most likely to need cherry-picking** (shared behaviour, not brand-specific):

- quote forms and pricing (`FrontendController`, `get-qoute.blade.php`)
- the central-gateway client
- bridge services and `EmployeeSyncController`
- login and session handling

Keep this list current. When a fix touches one of these, cherry-pick it the same day — drift is
cheap to prevent and expensive to unpick.

---

## 4. What is already on `main` and will reach Hello only

The 13 commits ahead of `crazyrays` are 11 documentation commits plus two code changes:

| Commit | Change | Safe for CrazyRays? |
|---|---|---|
| `5d9c41a` | W-07 per-service quote forms, Vehicle Price removed | yes — public marketing site, which florida does not serve |
| `ee5f758` | W-05 login page wording | yes — written with `{{ $brandName }}`, so it renders the right brand either way |

Neither is harmful to CrazyRays, so they are reasonable cherry-pick candidates later if you want the
quote fixes on both. They are **not** needed for CrazyRays to keep working.

---

## 5. Order of work from here

1. ✅ `crazyrays` branches created and pushed in both repos
2. **Switch the two CrazyRays deployments** to the `crazyrays` branch — §2
3. Confirm florida and hr.crazyrays still log in and show orders
4. **Then** start Hello pipeline development on `main`

Step 3 matters: it proves the branch switch was a no-op before any Hello work lands on `main` and
makes the two diverge.
