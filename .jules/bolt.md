## 2025-01-24 - [N+1 Query in dressColor]
**Learning:** The `dressColor` method was performing a database query for every character in the input string. Since the target table `colortb` only has 8 rows, fetching the whole table once and mapping in memory is significantly faster.
**Action:** Always check table size for small lookup tables; fetching once is better than multiple queries in a loop.
