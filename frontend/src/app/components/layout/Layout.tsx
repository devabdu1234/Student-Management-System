import { Outlet, useLocation, Navigate } from "react-router";
import { Sidebar } from "./Sidebar";
import { Header } from "./Header";
import { useAuth } from "../../../lib/auth";

const pageMeta: Record<string, { title: string; subtitle?: string }> = {
  "/": { title: "Dashboard", subtitle: "Welcome back" },
  "/students": { title: "Student Management", subtitle: "Manage and monitor your students" },
  "/subjects": { title: "Subject Management", subtitle: "Your assigned subjects and schedules" },
  "/attendance": { title: "Attendance Management", subtitle: "Track and record class attendance" },
  "/assessments": { title: "Assessment Management", subtitle: "Quizzes, exams, and grading" },
  "/eligibility": { title: "Eligibility Tracking", subtitle: "Monitor student eligibility status" },
  "/reports": { title: "Reports & Analytics", subtitle: "Academic performance insights" },
  "/users": { title: "User Management", subtitle: "Manage system users and roles" },
  "/profile": { title: "My Profile", subtitle: "Account settings and preferences" },
};

export function Layout() {
  const { pathname } = useLocation();
  const { user, loading } = useAuth();
  const meta = pageMeta[pathname] ?? { title: "ICST AMS" };

  if (loading) return <div className="flex items-center justify-center h-screen" style={{ background: "var(--background)" }}><div className="w-8 h-8 border-2 border-navy/30 border-t-navy rounded-full animate-spin" /></div>;
  if (!user) return <Navigate to="/login" replace />;

  return (
    <div className="flex h-screen w-screen overflow-hidden" style={{ background: "var(--background)", fontFamily: "'DM Sans', sans-serif" }}>
      <Sidebar />
      <div className="flex flex-col flex-1 min-w-0 overflow-hidden">
        <Header title={meta.title} subtitle={meta.subtitle} />
        <main className="flex-1 overflow-y-auto p-6">
          <Outlet />
        </main>
      </div>
    </div>
  );
}
