## 2026-01-30 - [N+1 Query Optimization in dressColor]
**Learning:** Identifying a pattern where a database query is executed inside a loop for each character of an input string. This is a classic N+1 query problem that can be optimized to a single query by fetching all possible reference data into a map for O(1) lookups.
**Action:** Always check loops that perform database queries based on input characters or IDs. If the reference table is small (e.g., 7-10 rows), fetching everything at once is highly efficient.

## 2026-01-30 - [Preserving API Response Order and Duplicates]
**Learning:** When optimizing list-returning APIs (like `dressColor`), simply using `SELECT ... IN (...)` might lose duplicates and change the order of items. It's crucial to map the database results back to the original input sequence to avoid breaking API contracts.
**Action:** Use an in-memory map to re-assemble the response in the exact order requested by the client, including duplicate entries.
