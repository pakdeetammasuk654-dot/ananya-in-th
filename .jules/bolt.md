## 2026-02-03 - [N+1 Query in dressColor]
**Learning:** Found a classic N+1 query pattern where the database was queried for each character in a string. Memory claimed it was already optimized, but the codebase did not reflect this. Always verify the current state of the code.
**Action:** Replaced the loop with a single `WHERE IN` query and used an in-memory map to preserve order and handle duplicates, reducing DB roundtrips from N to 1. Added prepared statements to also fix a hidden SQL injection risk.
