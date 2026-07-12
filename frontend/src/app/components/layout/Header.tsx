import { Bell, Search, ChevronDown, LogOut } from "lucide-react";
import { useState } from "react";
import { useNavigate } from "react-router";
import { useAuth } from "../../../lib/auth";

interface HeaderProps { title: string; subtitle?: string }

export function Header({ title, subtitle }: HeaderProps) {
  const [searchFocused, setSearchFocused] = useState(false);
  const [menuOpen, setMenuOpen] = useState(false);
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const initials = user?.name?.split(" ").map((n) => n[0]).join("").slice(0, 2) || "??";

  return (
    <header className="flex items-center justify-between px-6 py-4 border-b bg-white shrink-0" style={{ borderColor: "rgba(13,31,60,0.08)", fontFamily: "'DM Sans', sans-serif" }}>
      <div>
        <h1 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 20, color: "var(--navy)", lineHeight: 1.3 }}>{title}</h1>
        {subtitle && <p style={{ fontSize: 13, color: "var(--muted-foreground)", marginTop: 2 }}>{subtitle}</p>}
      </div>
      <div className="flex items-center gap-3">
        <div className="flex items-center gap-2 px-3 py-2 rounded-lg transition-all" style={{ background: searchFocused ? "#fff" : "var(--muted)", border: `1.5px solid ${searchFocused ? "var(--gold)" : "transparent"}`, width: searchFocused ? 220 : 180 }}>
          <Search size={15} style={{ color: "var(--muted-foreground)" }} />
          <input type="text" placeholder="Search..." onFocus={() => setSearchFocused(true)} onBlur={() => setSearchFocused(false)} className="bg-transparent outline-none w-full" style={{ fontSize: 13, color: "var(--navy)" }} />
        </div>
        <button className="relative w-9 h-9 flex items-center justify-center rounded-lg transition-all hover:bg-muted" style={{ background: "var(--muted)" }}>
          <Bell size={17} style={{ color: "var(--navy)" }} />
          <span className="absolute top-1.5 right-1.5 w-2 h-2 rounded-full" style={{ background: "var(--gold)" }} />
        </button>
        <div className="relative">
          <button onClick={() => setMenuOpen(!menuOpen)} className="flex items-center gap-2.5 pl-2 pr-3 py-1.5 rounded-xl transition-all hover:bg-muted" style={{ background: "var(--muted)" }}>
            <div className="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style={{ background: "var(--navy)", fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 12, color: "#fff" }}>{initials}</div>
            <div className="text-left hidden sm:block">
              <div style={{ fontSize: 13, fontWeight: 500, color: "var(--navy)", lineHeight: 1.3 }}>{user?.name || "User"}</div>
              <div style={{ fontSize: 11, color: "var(--muted-foreground)" }}>{user?.role ? user.role.charAt(0).toUpperCase() + user.role.slice(1) : ""}</div>
            </div>
            <ChevronDown size={14} style={{ color: "var(--muted-foreground)" }} className="hidden sm:block" />
          </button>
          {menuOpen && (
            <div className="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border py-2 z-50" style={{ borderColor: "rgba(13,31,60,0.1)" }}>
              <button onClick={() => { setMenuOpen(false); navigate("/profile"); }} className="w-full text-left px-4 py-2 text-sm hover:bg-gray-50" style={{ color: "var(--navy)" }}>Profile</button>
              <button onClick={async () => { setMenuOpen(false); await logout(); navigate("/login"); }} className="w-full text-left px-4 py-2 text-sm hover:bg-red-50 flex items-center gap-2" style={{ color: "#d4183d" }}>
                <LogOut size={14} /> Sign Out
              </button>
            </div>
          )}
        </div>
      </div>
    </header>
  );
}
