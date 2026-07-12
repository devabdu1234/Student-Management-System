import { useState } from "react";
import { NavLink, useNavigate } from "react-router";
import { LayoutDashboard, Users, BookOpen, ClipboardCheck, FileText, ShieldCheck, BarChart3, UserCog, User, LogOut, ChevronLeft, ChevronRight, GraduationCap } from "lucide-react";
import { motion, AnimatePresence } from "motion/react";
import { cn } from "../../../lib/utils";
import { useAuth } from "../../../lib/auth";

const navItems = [
  { label: "Overview", items: [{ path: "/", icon: LayoutDashboard, label: "Dashboard" }] },
  { label: "Academic", items: [
    { path: "/students", icon: Users, label: "Students" },
    { path: "/subjects", icon: BookOpen, label: "Subjects" },
    { path: "/attendance", icon: ClipboardCheck, label: "Attendance" },
    { path: "/assessments", icon: FileText, label: "Assessments" },
  ]},
  { label: "Tracking", items: [
    { path: "/eligibility", icon: ShieldCheck, label: "Eligibility" },
    { path: "/reports", icon: BarChart3, label: "Reports" },
  ]},
  { label: "Administration", items: [
    { path: "/users", icon: UserCog, label: "User Management" },
    { path: "/profile", icon: User, label: "Profile" },
  ]},
];

export function Sidebar() {
  const [collapsed, setCollapsed] = useState(false);
  const navigate = useNavigate();
  const { logout } = useAuth();

  return (
    <motion.aside animate={{ width: collapsed ? 72 : 256 }} transition={{ duration: 0.25, ease: "easeInOut" }}
      className="relative flex flex-col shrink-0 h-full overflow-hidden" style={{ background: "var(--navy)", fontFamily: "'DM Sans', sans-serif" }}>
      <div className="flex items-center gap-3 px-4 py-5 border-b" style={{ borderColor: "rgba(255,255,255,0.08)" }}>
        <div className="shrink-0 w-9 h-9 rounded-lg flex items-center justify-center" style={{ background: "var(--gold)" }}>
          <GraduationCap size={20} style={{ color: "var(--navy)" }} />
        </div>
        <AnimatePresence>
          {!collapsed && (
            <motion.div initial={{ opacity: 0, x: -8 }} animate={{ opacity: 1, x: 0 }} exit={{ opacity: 0, x: -8 }} transition={{ duration: 0.18 }} className="overflow-hidden">
              <div className="whitespace-nowrap" style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 16, color: "#fff", lineHeight: 1.2 }}>ICST</div>
              <div className="whitespace-nowrap" style={{ fontWeight: 400, fontSize: 11, color: "var(--gold-light)", letterSpacing: "0.06em" }}>ACADEMIC SYSTEM</div>
            </motion.div>
          )}
        </AnimatePresence>
      </div>

      <nav className="flex-1 overflow-y-auto py-4 px-3 space-y-5 scrollbar-hide">
        {navItems.map((group) => (
          <div key={group.label}>
            <AnimatePresence>
              {!collapsed && <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }} className="px-2 mb-1.5" style={{ fontSize: 10, fontWeight: 600, letterSpacing: "0.1em", color: "rgba(255,255,255,0.35)", textTransform: "uppercase" }}>{group.label}</motion.div>}
            </AnimatePresence>
            <div className="space-y-0.5">
              {group.items.map((item) => (
                <NavLink key={item.path} to={item.path} end={item.path === "/"}
                  className={({ isActive }) => cn("flex items-center gap-3 px-2 py-2.5 rounded-lg transition-all duration-150 group relative", isActive ? "text-white" : "text-white/60 hover:text-white/90 hover:bg-white/5")}>
                  {({ isActive }) => (
                    <>
                      {isActive && <motion.div layoutId="activeNav" className="absolute inset-0 rounded-lg" style={{ background: "var(--gold)", opacity: 0.15 }} transition={{ type: "spring", bounce: 0.2, duration: 0.4 }} />}
                      {isActive && <div className="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 rounded-full" style={{ background: "var(--gold)" }} />}
                      <item.icon size={18} className="shrink-0 relative z-10" style={{ color: isActive ? "var(--gold)" : undefined }} />
                      <AnimatePresence>{!collapsed && <motion.span initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }} className="whitespace-nowrap relative z-10" style={{ fontSize: 14, fontWeight: isActive ? 500 : 400 }}>{item.label}</motion.span>}</AnimatePresence>
                    </>
                  )}
                </NavLink>
              ))}
            </div>
          </div>
        ))}
      </nav>

      <div className="p-3 border-t" style={{ borderColor: "rgba(255,255,255,0.08)" }}>
        <button onClick={async () => { await logout(); navigate("/login"); }}
          className="flex items-center gap-3 px-2 py-2.5 rounded-lg w-full transition-all duration-150 text-white/50 hover:text-red-400 hover:bg-white/5">
          <LogOut size={18} className="shrink-0" />
          <AnimatePresence>{!collapsed && <motion.span initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }} style={{ fontSize: 14 }} className="whitespace-nowrap">Sign Out</motion.span>}</AnimatePresence>
        </button>
      </div>

      <button onClick={() => setCollapsed(!collapsed)}
        className="absolute -right-3 top-20 w-6 h-6 rounded-full border flex items-center justify-center transition-all hover:scale-110 z-50"
        style={{ background: "var(--navy)", borderColor: "rgba(255,255,255,0.15)", color: "rgba(255,255,255,0.6)" }}>
        {collapsed ? <ChevronRight size={12} /> : <ChevronLeft size={12} />}
      </button>
    </motion.aside>
  );
}
