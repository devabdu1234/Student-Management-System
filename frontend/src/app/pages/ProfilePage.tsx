import { useState } from "react";
import { User, Mail, Phone, MapPin, BookOpen, Edit2, Camera, Check, Bell, Shield, Key, Globe } from "lucide-react";
import { motion } from "motion/react";
import { useAuth } from "../../lib/auth";

export function ProfilePage() {
  const { user } = useAuth();
  const [tab, setTab] = useState<"profile" | "settings" | "security">("profile");
  const [editMode, setEditMode] = useState(false);
  const [saved, setSaved] = useState(false);
  const [profile, setProfile] = useState({
    name: user?.name || "User",
    title: user?.role ? user.role.charAt(0).toUpperCase() + user.role.slice(1) : "Faculty",
    email: user?.email || "",
    phone: user?.phone || "+63...",
    department: "Department of Computer Studies",
    office: "Faculty Room 204, Main Building",
    bio: "Faculty member at ICST. Committed to technology-enhanced learning.",
    employeeId: user?.id ? `ICST-FAC-${String(user.id).padStart(4, '0')}` : "ICST-FAC-0001",
    yearJoined: "2023",
  });

  const initials = profile.name.split(" ").map((n) => n[0]).join("").slice(0, 2);

  const handleSave = () => {
    setSaved(true);
    setEditMode(false);
    setTimeout(() => setSaved(false), 3000);
  };

  return (
    <div style={{ fontFamily: "'DM Sans', sans-serif" }} className="max-w-4xl mx-auto">
      <motion.div initial={{ opacity: 0, y: 16 }} animate={{ opacity: 1, y: 0 }}
        className="bg-white rounded-2xl border overflow-hidden mb-5"
        style={{ borderColor: "rgba(13,31,60,0.08)" }}>
        <div className="h-28 relative" style={{ background: "linear-gradient(135deg, var(--navy) 0%, #1a3a6e 100%)" }}>
          <div className="absolute inset-0 opacity-10" style={{ backgroundImage: "radial-gradient(circle at 20% 50%, var(--gold) 0%, transparent 60%)" }} />
        </div>
        <div className="px-6 pb-6">
          <div className="flex items-end justify-between -mt-10 mb-4">
            <div className="relative">
              <div className="w-20 h-20 rounded-2xl border-4 border-white flex items-center justify-center"
                style={{ background: "var(--navy)", fontFamily: "'Outfit', sans-serif", fontWeight: 800, fontSize: 26, color: "var(--gold)", boxShadow: "0 4px 16px rgba(13,31,60,0.25)" }}>
                {initials}
              </div>
              <button className="absolute -bottom-1 -right-1 w-6 h-6 rounded-full flex items-center justify-center border-2 border-white"
                style={{ background: "var(--gold)" }}>
                <Camera size={11} style={{ color: "var(--navy)" }} />
              </button>
            </div>
            <div className="flex items-center gap-2 pb-1">
              {saved && (
                <motion.span initial={{ opacity: 0, scale: 0.8 }} animate={{ opacity: 1, scale: 1 }}
                  className="flex items-center gap-1.5 px-3 py-1.5 rounded-lg"
                  style={{ background: "#f0fdf4", color: "#16a34a", fontSize: 12, fontWeight: 500 }}>
                  <Check size={13} /> Changes saved
                </motion.span>
              )}
              <button onClick={() => editMode ? handleSave() : setEditMode(true)}
                className="flex items-center gap-2 px-4 py-2 rounded-xl transition-all hover:opacity-90"
                style={{ background: editMode ? "var(--gold)" : "var(--navy)", color: editMode ? "var(--navy)" : "#fff", fontSize: 13, fontWeight: 600 }}>
                {editMode ? <><Check size={14} /> Save Changes</> : <><Edit2 size={14} /> Edit Profile</>}
              </button>
            </div>
          </div>
          <div>
            {editMode ? (
              <input value={profile.name} onChange={(e) => setProfile((p) => ({ ...p, name: e.target.value }))}
                className="w-full px-3 py-1.5 rounded-lg outline-none mb-1"
                style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 22, color: "var(--navy)", background: "var(--muted)", border: "1.5px solid var(--gold)" }} />
            ) : (
              <h2 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 22, color: "var(--navy)" }}>{profile.name}</h2>
            )}
            <p style={{ fontSize: 14, color: "var(--muted-foreground)", marginTop: 2 }}>{profile.title} · {profile.department}</p>
            <p style={{ fontSize: 12, color: "var(--muted-foreground)", marginTop: 4, fontFamily: "'DM Mono', monospace" }}>
              Employee ID: {profile.employeeId} · Since {profile.yearJoined}
            </p>
          </div>
        </div>
      </motion.div>

      <div className="flex items-center gap-1 p-1 rounded-xl w-fit mb-5" style={{ background: "var(--muted)" }}>
        {[{ key: "profile", label: "Profile Info" }, { key: "settings", label: "Preferences" }, { key: "security", label: "Security" }].map((t) => (
          <button key={t.key} onClick={() => setTab(t.key as any)}
            className="px-5 py-2 rounded-lg transition-all"
            style={{ background: tab === t.key ? "#fff" : "transparent", color: tab === t.key ? "var(--navy)" : "var(--muted-foreground)", fontSize: 13, fontWeight: tab === t.key ? 600 : 400, boxShadow: tab === t.key ? "0 1px 4px rgba(13,31,60,0.1)" : "none" }}>
            {t.label}
          </button>
        ))}
      </div>

      {tab === "profile" && (
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
          <div className="lg:col-span-2 bg-white rounded-2xl border p-6" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
            <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 15, color: "var(--navy)", marginBottom: 20 }}>Contact Information</h3>
            <div className="space-y-5">
              {[{ icon: Mail, label: "Email Address", key: "email", type: "email" },
                { icon: Phone, label: "Phone Number", key: "phone", type: "text" },
                { icon: MapPin, label: "Office Location", key: "office", type: "text" },
              ].map((field) => (
                <div key={field.key} className="flex items-start gap-3">
                  <div className="w-8 h-8 rounded-lg flex items-center justify-center mt-1 shrink-0" style={{ background: "var(--muted)" }}>
                    <field.icon size={15} style={{ color: "var(--navy)" }} />
                  </div>
                  <div className="flex-1">
                    <div style={{ fontSize: 11, color: "var(--muted-foreground)", marginBottom: 4, textTransform: "uppercase", letterSpacing: "0.05em", fontWeight: 600 }}>{field.label}</div>
                    {editMode ? (
                      <input type={field.type} value={(profile as any)[field.key]}
                        onChange={(e) => setProfile((p) => ({ ...p, [field.key]: e.target.value }))}
                        className="w-full px-3 py-2 rounded-lg outline-none"
                        style={{ fontSize: 14, color: "var(--navy)", background: "var(--muted)", border: "1.5px solid var(--gold)" }} />
                    ) : (
                      <div style={{ fontSize: 14, color: "var(--navy)" }}>{(profile as any)[field.key]}</div>
                    )}
                  </div>
                </div>
              ))}
            </div>
            <div className="mt-6 pt-6 border-t" style={{ borderColor: "rgba(13,31,60,0.07)" }}>
              <div style={{ fontSize: 11, color: "var(--muted-foreground)", marginBottom: 8, textTransform: "uppercase", letterSpacing: "0.05em", fontWeight: 600 }}>Biography</div>
              {editMode ? (
                <textarea value={profile.bio} onChange={(e) => setProfile((p) => ({ ...p, bio: e.target.value }))}
                  className="w-full px-3 py-2 rounded-lg outline-none resize-none" rows={4}
                  style={{ fontSize: 13, color: "var(--navy)", background: "var(--muted)", border: "1.5px solid var(--gold)", lineHeight: 1.7 }} />
              ) : (
                <p style={{ fontSize: 13, color: "var(--muted-foreground)", lineHeight: 1.8 }}>{profile.bio}</p>
              )}
            </div>
          </div>
          <div className="space-y-4">
            <div className="bg-white rounded-2xl border p-5" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
              <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 14, color: "var(--navy)", marginBottom: 12 }}>Account Details</h3>
              <div className="space-y-3">
                {[
                  { label: "Role", value: profile.title },
                  { label: "Email", value: profile.email },
                  { label: "User ID", value: profile.employeeId },
                ].map((stat) => (
                  <div key={stat.label} className="flex justify-between items-center py-1.5 border-b last:border-0" style={{ borderColor: "rgba(13,31,60,0.06)" }}>
                    <span style={{ fontSize: 12, color: "var(--muted-foreground)" }}>{stat.label}</span>
                    <span style={{ fontSize: 13, fontWeight: 600, color: "var(--navy)" }}>{stat.value}</span>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      )}

      {tab === "settings" && (
        <div className="bg-white rounded-2xl border p-6" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
          <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 15, color: "var(--navy)", marginBottom: 20 }}>Preferences</h3>
          <div className="space-y-5">
            {[
              { icon: Bell, label: "Email Notifications", desc: "Receive email summaries for attendance and grades", defaultOn: true },
              { icon: Bell, label: "Eligibility Alerts", desc: "Alert when students fall below eligibility threshold", defaultOn: true },
              { icon: Globe, label: "Report Auto-generation", desc: "Auto-generate weekly reports every Monday", defaultOn: false },
            ].map((pref, i) => {
              const [on, setOn] = useState(pref.defaultOn);
              return (
                <div key={i} className="flex items-center justify-between py-3 border-b last:border-0" style={{ borderColor: "rgba(13,31,60,0.06)" }}>
                  <div className="flex items-start gap-3">
                    <div className="w-8 h-8 rounded-lg flex items-center justify-center" style={{ background: "var(--muted)" }}>
                      <pref.icon size={15} style={{ color: "var(--navy)" }} />
                    </div>
                    <div>
                      <div style={{ fontSize: 14, fontWeight: 500, color: "var(--navy)" }}>{pref.label}</div>
                      <div style={{ fontSize: 12, color: "var(--muted-foreground)" }}>{pref.desc}</div>
                    </div>
                  </div>
                  <button onClick={() => setOn(!on)}
                    className="relative w-11 h-6 rounded-full transition-all"
                    style={{ background: on ? "var(--navy)" : "var(--muted)" }}>
                    <div className="absolute top-0.5 w-5 h-5 rounded-full bg-white shadow transition-all"
                      style={{ left: on ? "calc(100% - 22px)" : "2px" }} />
                  </button>
                </div>
              );
            })}
          </div>
        </div>
      )}

      {tab === "security" && (
        <div className="bg-white rounded-2xl border p-6" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
          <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 15, color: "var(--navy)", marginBottom: 20 }}>Security Settings</h3>
          <div className="space-y-5">
            <div className="p-4 rounded-xl" style={{ background: "rgba(13,31,60,0.04)", border: "1px solid rgba(13,31,60,0.08)" }}>
              <div className="flex items-center justify-between">
                <div className="flex items-center gap-3">
                  <Key size={16} style={{ color: "var(--navy)" }} />
                  <div>
                    <div style={{ fontSize: 14, fontWeight: 500, color: "var(--navy)" }}>Password</div>
                    <div style={{ fontSize: 12, color: "var(--muted-foreground)" }}>Change your account password</div>
                  </div>
                </div>
                <button className="px-3 py-1.5 rounded-lg text-sm font-medium" style={{ background: "var(--navy)", color: "#fff" }}>Change Password</button>
              </div>
            </div>
            <div className="p-4 rounded-xl" style={{ background: "rgba(13,31,60,0.04)", border: "1px solid rgba(13,31,60,0.08)" }}>
              <div className="flex items-center justify-between">
                <div className="flex items-center gap-3">
                  <Shield size={16} style={{ color: "var(--navy)" }} />
                  <div>
                    <div style={{ fontSize: 14, fontWeight: 500, color: "var(--navy)" }}>Two-Factor Authentication</div>
                    <div style={{ fontSize: 12, color: "var(--muted-foreground)" }}>Currently disabled</div>
                  </div>
                </div>
                <button className="px-3 py-1.5 rounded-lg text-sm font-medium" style={{ background: "var(--gold)", color: "var(--navy)" }}>Enable 2FA</button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
