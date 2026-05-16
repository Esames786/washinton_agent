import sys

with open(r'd:/laragon2/www/washinton_agent/resources/views/main/register/index.blade.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

print(f"Total lines: {len(lines)}")
print(f"Line 287 (0-idx 286): {lines[286][:80]!r}")
print(f"Line 2169 (0-idx 2168): {lines[2168]!r}")
print(f"Line 2170 (0-idx 2169): {lines[2169][:80]!r}")
