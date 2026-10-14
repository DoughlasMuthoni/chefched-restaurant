import { useEffect, useState } from 'react';
import { api } from '../api';
import ImageUploadField from '../components/ImageUploadField';

const BLANK_CONTACT = { phone: '', whatsapp: '', email: '', instagram_url: '', tiktok_url: '', facebook_url: '' };
const BLANK_SLIDE = { image: '', alt: '', years_badge: '7', years_label: 'Yrs', h1: '', h2: '', h3: '', paragraph: '', btn1_label: '', btn1_href: '', btn2_label: '', btn2_href: '' };
const BLANK_HOMEPAGE = {
  hero: [{ ...BLANK_SLIDE }, { ...BLANK_SLIDE }],
  chef_intro: { eyebrow: '', heading: '', paragraph1: '', paragraph2: '', image: '', stat_badge: '', chef_name: '', chef_tagline: '' },
  philosophy: { eyebrow: '', heading: '', intro: '', image: '', pillars: [{ icon: '', heading: '', text: '' }, { icon: '', heading: '', text: '' }, { icon: '', heading: '', text: '' }] },
};

export default function SiteSettingsManager() {
  const [contact, setContact] = useState(BLANK_CONTACT);
  const [homepage, setHomepage] = useState(BLANK_HOMEPAGE);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [savedMsg, setSavedMsg] = useState('');
  const [savingContact, setSavingContact] = useState(false);
  const [savingHomepage, setSavingHomepage] = useState(false);

  useEffect(() => {
    (async () => {
      setLoading(true);
      try {
        const res = await api.getSettings();
        if (res.data.contact_info) setContact({ ...BLANK_CONTACT, ...res.data.contact_info });
        if (res.data.homepage) {
          const hp = res.data.homepage;
          setHomepage({
            hero: [hp.hero?.[0] || BLANK_SLIDE, hp.hero?.[1] || BLANK_SLIDE],
            chef_intro: { ...BLANK_HOMEPAGE.chef_intro, ...(hp.chef_intro || {}) },
            philosophy: {
              ...BLANK_HOMEPAGE.philosophy,
              ...(hp.philosophy || {}),
              pillars: hp.philosophy?.pillars?.length === 3 ? hp.philosophy.pillars : BLANK_HOMEPAGE.philosophy.pillars,
            },
          });
        }
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    })();
  }, []);

  function updateHero(i, field, value) {
    setHomepage((prev) => {
      const hero = [...prev.hero];
      hero[i] = { ...hero[i], [field]: value };
      return { ...prev, hero };
    });
  }

  function updateChefIntro(field, value) {
    setHomepage((prev) => ({ ...prev, chef_intro: { ...prev.chef_intro, [field]: value } }));
  }

  function updatePhilosophy(field, value) {
    setHomepage((prev) => ({ ...prev, philosophy: { ...prev.philosophy, [field]: value } }));
  }

  function updatePillar(i, field, value) {
    setHomepage((prev) => {
      const pillars = [...prev.philosophy.pillars];
      pillars[i] = { ...pillars[i], [field]: value };
      return { ...prev, philosophy: { ...prev.philosophy, pillars } };
    });
  }

  async function saveContact() {
    setSavingContact(true);
    setError('');
    setSavedMsg('');
    try {
      await api.updateSettings('contact_info', contact);
      setSavedMsg('Contact & socials saved.');
    } catch (err) {
      setError(err.message);
    } finally {
      setSavingContact(false);
    }
  }

  async function saveHomepage() {
    setSavingHomepage(true);
    setError('');
    setSavedMsg('');
    try {
      await api.updateSettings('homepage', homepage);
      setSavedMsg('Homepage content saved.');
    } catch (err) {
      setError(err.message);
    } finally {
      setSavingHomepage(false);
    }
  }

  if (loading) return <p className="muted">Loading…</p>;

  return (
    <div className="inbox settings-page">
      {error && <div className="alert alert-error">{error}</div>}
      {savedMsg && <div className="alert alert-success">{savedMsg}</div>}

      <section className="edit-panel">
        <h3>Contact &amp; Socials</h3>
        <p className="muted small">Shown in the footer of every page, plus the contact and booking pages.</p>
        <label>Phone<input value={contact.phone} onChange={(e) => setContact({ ...contact, phone: e.target.value })} /></label>
        <label>WhatsApp<input value={contact.whatsapp} onChange={(e) => setContact({ ...contact, whatsapp: e.target.value })} /></label>
        <label>Email<input value={contact.email} onChange={(e) => setContact({ ...contact, email: e.target.value })} /></label>
        <label>Instagram URL<input value={contact.instagram_url} onChange={(e) => setContact({ ...contact, instagram_url: e.target.value })} /></label>
        <label>TikTok URL<input value={contact.tiktok_url} onChange={(e) => setContact({ ...contact, tiktok_url: e.target.value })} /></label>
        <label>Facebook URL<input value={contact.facebook_url} onChange={(e) => setContact({ ...contact, facebook_url: e.target.value })} /></label>
        <div className="edit-panel-actions">
          <button className="btn-primary" onClick={saveContact} disabled={savingContact}>{savingContact ? 'Saving…' : 'Save Contact & Socials'}</button>
        </div>
      </section>

      <section className="edit-panel">
        <h3>Homepage Content</h3>
        <p className="muted small">The hero banner, "More Than A Chef" intro and Culinary Philosophy section — saved together below.</p>

        <h4>Hero Banner</h4>
        {homepage.hero.map((slide, i) => (
          <fieldset key={i} className="settings-fieldset">
            <legend>Slide {i + 1}</legend>
            <ImageUploadField label="Background Image" folder="homepage" value={slide.image} onChange={(path) => updateHero(i, 'image', path)} />
            <label>Heading 1<input value={slide.h1 || ''} onChange={(e) => updateHero(i, 'h1', e.target.value)} /></label>
            <label>Heading 2<input value={slide.h2 || ''} onChange={(e) => updateHero(i, 'h2', e.target.value)} /></label>
            <label>Heading 3<input value={slide.h3 || ''} onChange={(e) => updateHero(i, 'h3', e.target.value)} /></label>
            <label>Paragraph<textarea rows={2} value={slide.paragraph || ''} onChange={(e) => updateHero(i, 'paragraph', e.target.value)} /></label>
            <label>Button 1 Label<input value={slide.btn1_label || ''} onChange={(e) => updateHero(i, 'btn1_label', e.target.value)} /></label>
            <label>Button 1 Link<input value={slide.btn1_href || ''} onChange={(e) => updateHero(i, 'btn1_href', e.target.value)} /></label>
            <label>Button 2 Label<input value={slide.btn2_label || ''} onChange={(e) => updateHero(i, 'btn2_label', e.target.value)} /></label>
            <label>Button 2 Link<input value={slide.btn2_href || ''} onChange={(e) => updateHero(i, 'btn2_href', e.target.value)} /></label>
          </fieldset>
        ))}

        <h4>"More Than A Chef"</h4>
        <label>Eyebrow<input value={homepage.chef_intro.eyebrow} onChange={(e) => updateChefIntro('eyebrow', e.target.value)} /></label>
        <label>Heading<input value={homepage.chef_intro.heading} onChange={(e) => updateChefIntro('heading', e.target.value)} /></label>
        <label>Paragraph 1<textarea rows={2} value={homepage.chef_intro.paragraph1} onChange={(e) => updateChefIntro('paragraph1', e.target.value)} /></label>
        <label>Paragraph 2<textarea rows={2} value={homepage.chef_intro.paragraph2} onChange={(e) => updateChefIntro('paragraph2', e.target.value)} /></label>
        <ImageUploadField label="Chef Photo" folder="homepage" value={homepage.chef_intro.image} onChange={(path) => updateChefIntro('image', path)} />
        <label>Stat Badge (e.g. "7 Years Experience")<input value={homepage.chef_intro.stat_badge} onChange={(e) => updateChefIntro('stat_badge', e.target.value)} /></label>
        <label>Chef Name<input value={homepage.chef_intro.chef_name} onChange={(e) => updateChefIntro('chef_name', e.target.value)} /></label>
        <label>Tagline<input value={homepage.chef_intro.chef_tagline} onChange={(e) => updateChefIntro('chef_tagline', e.target.value)} /></label>

        <h4>Culinary Philosophy</h4>
        <label>Eyebrow<input value={homepage.philosophy.eyebrow} onChange={(e) => updatePhilosophy('eyebrow', e.target.value)} /></label>
        <label>Heading<input value={homepage.philosophy.heading} onChange={(e) => updatePhilosophy('heading', e.target.value)} /></label>
        <label>Intro Paragraph<textarea rows={3} value={homepage.philosophy.intro} onChange={(e) => updatePhilosophy('intro', e.target.value)} /></label>
        <ImageUploadField label="Photo" folder="homepage" value={homepage.philosophy.image} onChange={(path) => updatePhilosophy('image', path)} />
        {homepage.philosophy.pillars.map((pillar, i) => (
          <fieldset key={i} className="settings-fieldset">
            <legend>Pillar {i + 1}</legend>
            <label>Icon class (from css/flaticon.css, e.g. "hotmeal-dish")<input value={pillar.icon || ''} onChange={(e) => updatePillar(i, 'icon', e.target.value)} /></label>
            <label>Heading<input value={pillar.heading || ''} onChange={(e) => updatePillar(i, 'heading', e.target.value)} /></label>
            <label>Text<input value={pillar.text || ''} onChange={(e) => updatePillar(i, 'text', e.target.value)} /></label>
          </fieldset>
        ))}
        <div className="edit-panel-actions">
          <button className="btn-primary" onClick={saveHomepage} disabled={savingHomepage}>{savingHomepage ? 'Saving…' : 'Save Homepage Content'}</button>
        </div>
      </section>
    </div>
  );
}
