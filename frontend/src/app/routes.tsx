import { createBrowserRouter, Navigate } from "react-router";
import { LoginPage } from "./pages/LoginPage";
import { Layout } from "./components/layout/Layout";
import { DashboardPage } from "./pages/DashboardPage";
import { StudentManagement } from "./pages/StudentManagement";
import { SubjectManagement } from "./pages/SubjectManagement";
import { AttendanceManagement } from "./pages/AttendanceManagement";
import { AssessmentManagement } from "./pages/AssessmentManagement";
import { EligibilityTracking } from "./pages/EligibilityTracking";
import { ReportsPage } from "./pages/ReportsPage";
import { UserManagement } from "./pages/UserManagement";
import { ProfilePage } from "./pages/ProfilePage";

export const router = createBrowserRouter([
  {
    path: "/login",
    Component: LoginPage,
  },
  {
    path: "/",
    Component: Layout,
    children: [
      { index: true, Component: DashboardPage },
      { path: "students", Component: StudentManagement },
      { path: "subjects", Component: SubjectManagement },
      { path: "attendance", Component: AttendanceManagement },
      { path: "assessments", Component: AssessmentManagement },
      { path: "eligibility", Component: EligibilityTracking },
      { path: "reports", Component: ReportsPage },
      { path: "users", Component: UserManagement },
      { path: "profile", Component: ProfilePage },
    ],
  },
  { path: "*", Component: () => <Navigate to="/" replace /> },
]);
