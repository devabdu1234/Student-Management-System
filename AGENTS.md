# ICST Academic Management System — Progress Log

## Goal
- Complete the ICST Academic Management System to satisfy HDIT21193 assignment requirements and integrate the new Vite/React/shadcn front-end with the existing PHP backend.

## Constraints & Preferences
- All DB queries must use PDO prepared statements — no raw SQL concatenation
- Passwords must use bcrypt only (password_hash/verify) — no MD5 fallback
- Roles: Admin (full sys access), Lecturer (entity CRUD), Student/Parent (view portals)
- Must include assignment-required pages: about-us, features, contact-us, register, admin_dashboard, manage_features, manage_user + CRUD sub-pages
- Maintain all original entity CRUD pages + role-specific portals
- Every entity page must support insert, update, AND delete operations
- All system pages must communicate with MariaDB via PDO
- Do not redesign the UI, remove existing functionality, or change the tech stack
- New front-end uses Vite + React 18 + TypeScript + Tailwind v4 + shadcn/ui + MUI + Recharts; must connect to PHP backend via REST API

## Progress
### Done
- Git configured (`devabdu1234` / `03241091@icst.edu.lk`) and initial commit with 4782 files pushed to GitHub
- Apache (port 80) and XAMPP MariaDB (port 3306) running
- **PHP API Router**: `C:\xampp\htdocs\icst\api\index.php` — full REST endpoints for login, logout, me, users CRUD, students CRUD, teachers, subjects, classes, attendance, exams, examresults, dashboard, features, schedules
- **React API Client**: `frontend\src\lib\api.ts` — typed fetch wrapper for all `/api/*` endpoints
- **Auth Context**: `frontend\src\lib\auth.tsx` — session restore on mount, login/logout, loading state
- **Pages Rewritten** (replace hardcoded data with API calls):
  - `LoginPage.tsx` — real `useAuth().login()` call
  - `DashboardPage.tsx` — fetches real stats from `/api/dashboard`
  - `Layout.tsx` — auth guard with loading spinner + redirect
  - `Header.tsx` — real user initials/name/role from auth context
  - `Sidebar.tsx` — real logout button, active nav styling
  - `StudentManagement.tsx` — full CRUD (create/edit/delete) via API
  - `UserManagement.tsx` — full CRUD (create/edit/delete) via API
  - `SubjectManagement.tsx` — list/add/delete via API
  - `AttendanceManagement.tsx` — real subject/student lists, save to API
  - `AssessmentManagement.tsx` — real exams/exam results from API, score distribution chart
  - `ProfilePage.tsx` — real user data from auth context
- **Build**: `pnpm build` succeeds, output at `C:\xampp\htdocs\icst\dist\` (803 KB JS, 0.4 KB HTML)

### Remaining (lower priority — demo data preserved)
- `EligibilityTracking.tsx` — demo data (no dedicated API endpoint)
- `ReportsPage.tsx` — demo charts (aggregation endpoints would need to be built)

## Key Decisions
- React SPA builds to `icst/dist/`, served alongside PHP API at `/api/*` — same-origin auth, no CORS issues
- PHP endpoints reuse existing `config.php` PDO connection and session-based auth
- Auth context checks `/api/me` on mount to restore session on page refresh
- All new pages preserve original Figma UI design

## How to Test
1. **XAMPP**: Apache on port 80, MariaDB on port 3306 — both running
2. **PHP app**: `http://localhost/icst/login.php` — login: `admin@icst.ac.lk` / `1234`
3. **React dev server**: `pnpm dev` from `C:\xampp\htdocs\icst\frontend\` — proxies `/api` → `http://localhost`
4. **Built SPA**: Files at `C:\xampp\htdocs\icst\dist\` — point Apache to serve `index.html` for SPA routes
