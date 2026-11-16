{{-- The old manual "Check Price" / "Previous Prices" buttons send the quote to a
     "Check Price" account, who types the price in by hand. Hidden since batch 5;
     turned back on temporarily while Central Dispatch pricing is down (16 Nov 2026).
     OLD_CHECK_PRICE=true in .env shows them, false hides them again. --}}
@unless (config('app.old_check_price'))
<style>#checkPrice, #previousCheckPrice { display: none !important; }</style>
@endunless
