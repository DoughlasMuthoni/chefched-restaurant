import { BrowserRouter } from 'react-router-dom';
import { AuthProvider, useAuth } from './AuthContext';
import Login from './pages/Login';
import Dashboard from './pages/Dashboard';
import './App.css';

function AppShell() {
  const { username, loading } = useAuth();

  if (loading) {
    return <div className="login-screen"><p className="muted">Loading…</p></div>;
  }

  return username ? <Dashboard /> : <Login />;
}

export default function App() {
  return (
    <BrowserRouter basename="/admin">
      <AuthProvider>
        <AppShell />
      </AuthProvider>
    </BrowserRouter>
  );
}
