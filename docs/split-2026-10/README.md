# Hello / CrazyRays separation + Hello website rebuild

Requirements and plan, 12 October 2026. **Documentation only — no code or data has been changed.**

| Document | What it covers |
|---|---|
| [01-SPLIT-ARCHITECTURE.md](01-SPLIT-ARCHITECTURE.md) | Separating the shared database and codebase into two independent systems. Includes a live read-only audit of all 257 tables. |
| [02-WEBSITE-CHANGES.md](02-WEBSITE-CHANGES.md) | The seven requested hellotransport.com changes (W-01 … W-07), from the client email and 13 screenshots. |
| [03-START-HERE.md](03-START-HERE.md) | Sequencing, the three blocking questions, and what to do first. |

## The short version

The split is **feasible and simple**. Per the client rule of 13 Oct 2026, **all data is copied to
both sides**; only `user` is split (on `is_crazyrays`), and `cr_applications` / `cr_campaigns` go to
CrazyRays. See [`04-DATA-MIGRATION.md §2`](04-DATA-MIGRATION.md).

Split the systems **before** building the website changes, or every website item has to be built
twice.

Three questions block the fork — see `03-START-HERE.md`.

| [04-DATA-MIGRATION.md](04-DATA-MIGRATION.md) | How the data moves into the new CrazyRays database. Measured, with a corrected table classification. |
| [TABLE-CLASSIFICATION.md](TABLE-CLASSIFICATION.md) | Generated per-table list. Regenerate at migration time; do not migrate from a stale copy. |
