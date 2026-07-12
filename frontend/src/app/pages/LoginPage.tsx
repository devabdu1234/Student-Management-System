import { useState } from "react";
import { useNavigate } from "react-router";
import { GraduationCap, Eye, EyeOff, ArrowRight, Lock, User } from "lucide-react";
import { motion } from "motion/react";
import { useAuth } from "../../lib/auth";

export function LoginPage() {
  const navigate = useNavigate();
  const { login } = useAuth();
  const [email, setEmail] = useState("admin@icst.ac.lk");
  const [password, setPassword] = useState("1234");
  const [showPass, setShowPass] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    setError("");
    setLoading(true);
    try {
      await login(email, password);
      navigate("/");
    } catch (err: any) {
      setError(err.message || "Login failed. Please check your credentials.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div
      className="min-h-screen w-full flex"
      style={{ fontFamily: "'DM Sans', sans-serif" }}
    >
      <div
        className="hidden lg:flex flex-col justify-between w-[45%] p-12"
        style={{ background: "var(--navy)" }}
      >
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 rounded-xl flex items-center justify-center" style={{ background: "var(--gold)" }}>
            <GraduationCap size={22} style={{ color: "var(--navy)" }} />
          </div>
          <div>
            <div style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 18, color: "#fff" }}>ICST</div>
            <div style={{ fontSize: 10, fontWeight: 500, letterSpacing: "0.12em", color: "var(--gold-light)", textTransform: "uppercase" }}>Academic Management</div>
          </div>
        </div>

        <div className="space-y-6 max-w-md">
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.2 }}>
            <div className="inline-block px-3 py-1.5 rounded-full mb-6" style={{ background: "rgba(200,169,81,0.15)", border: "1px solid rgba(200,169,81,0.3)" }}>
              <span style={{ fontSize: 12, color: "var(--gold)", fontWeight: 500 }}>Academic Year 2024-2025 - 2nd Semester</span>
            </div>
            <h2 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 38, color: "#fff", lineHeight: 1.2 }}>
              Simplify your<br />academic workflow.
            </h2>
            <p style={{ fontSize: 15, color: "rgba(255,255,255,0.55)", marginTop: 16, lineHeight: 1.7 }}>
              Manage students, track attendance, process assessments, and generate reports - all in one place.
            </p>
          </motion.div>
        </div>

        <p style={{ fontSize: 12, color: "rgba(255,255,255,0.25)" }}>
          (c) 2025 ICST Academic Management System. All rights reserved.
        </p>
      </div>

      <div className="flex-1 flex items-center justify-center px-6 py-12 bg-white">
        <motion.div initial={{ opacity: 0, x: 20 }} animate={{ opacity: 1, x: 0 }} transition={{ duration: 0.4 }} className="w-full max-w-sm">
          <div className="flex items-center gap-3 mb-10 lg:hidden">
            <div className="w-9 h-9 rounded-xl flex items-center justify-center" style={{ background: "var(--navy)" }}>
              <GraduationCap size={18} style={{ color: "var(--gold)" }} />
            </div>
            <div style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 18, color: "var(--navy)" }}>ICST AMS</div>
          </div>

          <div className="mb-8">
            <h1 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 28, color: "var(--navy)" }}>Welcome back</h1>
            <p style={{ fontSize: 14, color: "var(--muted-foreground)", marginTop: 6 }}>Sign in to your account</p>
          </div>

          <form onSubmit={handleLogin} className="space-y-4">
            <div>
              <label style={{ fontSize: 13, fontWeight: 500, color: "var(--navy)", display: "block", marginBottom: 6 }}>Email Address</label>
              <div className="relative">
                <User size={15} className="absolute left-3 top-1/2 -translate-y-1/2" style={{ color: "var(--muted-foreground)" }} />
                <input type="email" value={email} onChange={(e) => setEmail(e.target.value)}
                  className="w-full pl-9 pr-4 py-3 rounded-xl outline-none transition-all"
                  style={{ background: "var(--input-background)", fontSize: 14, color: "var(--navy)", border: "1.5px solid transparent" }}
                  onFocus={(e) => (e.target.style.border = "1.5px solid var(--gold)")}
                  onBlur={(e) => (e.target.style.border = "1.5px solid transparent")}
                  required />
              </div>
            </div>

            <div>
              <div className="flex justify-between mb-1.5">
                <label style={{ fontSize: 13, fontWeight: 500, color: "var(--navy)" }}>Password</label>
              </div>
              <div className="relative">
                <Lock size={15} className="absolute left-3 top-1/2 -translate-y-1/2" style={{ color: "var(--muted-foreground)" }} />
                <input type={showPass ? "text" : "password"} value={password} onChange={(e) => setPassword(e.target.value)}
                  className="w-full pl-9 pr-10 py-3 rounded-xl outline-none transition-all"
                  style={{ background: "var(--input-background)", fontSize: 14, color: "var(--navy)", border: "1.5px solid transparent" }}
                  onFocus={(e) => (e.target.style.border = "1.5px solid var(--gold)")}
                  onBlur={(e) => (e.target.style.border = "1.5px solid transparent")}
                  required />
                <button type="button" onClick={() => setShowPass(!showPass)} className="absolute right-3 top-1/2 -translate-y-1/2" style={{ color: "var(--muted-foreground)" }}>
                  {showPass ? <EyeOff size={15} /> : <Eye size={15} />}
                </button>
              </div>
            </div>

            {error && (
              <div className="px-3 py-2.5 rounded-lg" style={{ background: "#fff0f3", border: "1px solid #fecdd3" }}>
                <p style={{ fontSize: 13, color: "#d4183d" }}>{error}</p>
              </div>
            )}

            <button type="submit" disabled={loading}
              className="w-full flex items-center justify-center gap-2 py-3 rounded-xl transition-all mt-2"
              style={{ background: loading ? "rgba(13,31,60,0.6)" : "var(--navy)", color: "#fff", fontSize: 15, fontWeight: 600, fontFamily: "'Outfit', sans-serif", cursor: loading ? "not-allowed" : "pointer" }}>
              {loading ? (
                <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" />
              ) : (
                <>Sign In <ArrowRight size={16} /></>
              )}
            </button>
          </form>
        </motion.div>
      </div>
    </div>
  );
}
