import { NavLink, Route, Routes } from 'react-router-dom';
import { useAuth } from '../AuthContext';
import EnquiriesInbox from './EnquiriesInbox';
import MessagesInbox from './MessagesInbox';
import ServicesManager from './ServicesManager';
import GalleryManager from './GalleryManager';
import JournalManager from './JournalManager';
import SiteSettingsManager from './SiteSettingsManager';

export default function Dashboard() {
  const { username, logout } = useAuth();

  return (
    <div className="dashboard">
      <header className="dashboard-header">
        <h1>Njogu Nderitu — Admin</h1>
        <nav>
          <NavLink to="/" end>Booking Enquiries</NavLink>
          <NavLink to="/messages">Contact Messages</NavLink>
          <NavLink to="/services">Services</NavLink>
          <NavLink to="/gallery">Gallery</NavLink>
          <NavLink to="/journal">Journal</NavLink>
          <NavLink to="/settings">Site Settings</NavLink>
        </nav>
        <div className="dashboard-user">
          <span>{username}</span>
          <button onClick={logout}>Log out</button>
        </div>
      </header>
      <main>
        <Routes>
          <Route path="/" element={<EnquiriesInbox />} />
          <Route path="/messages" element={<MessagesInbox />} />
          <Route path="/services" element={<ServicesManager />} />
          <Route path="/gallery" element={<GalleryManager />} />
          <Route path="/journal" element={<JournalManager />} />
          <Route path="/settings" element={<SiteSettingsManager />} />
        </Routes>
      </main>
    </div>
  );
}
