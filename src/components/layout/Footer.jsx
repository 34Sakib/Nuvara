import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { Mail, ShieldCheck } from 'lucide-react';
import { useToastStore } from '../../store/toastStore';

const FacebookIcon = () => (
  <svg className="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
  </svg>
);

const InstagramIcon = () => (
  <svg className="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
  </svg>
);

const TwitterIcon = () => (
  <svg className="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z" />
  </svg>
);


export const Footer = () => {
  const { t } = useTranslation();
  const { addToast } = useToastStore();
  const [email, setEmail] = useState('');
  const [status, setStatus] = useState('');

  const handleSubscribe = (e) => {
    e.preventDefault();
    if (!email || !email.includes('@')) {
      setStatus('invalid');
      return;
    }
    setStatus('success');
    addToast(t('home.newsletter_success'), 'success');
    setEmail('');
    setTimeout(() => setStatus(''), 4000);
  };

  return (
    <footer className="bg-bg-secondary border-t border-border mt-20 transition-colors">
      {/* Newsletter Section */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-b border-border">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
          <div className="lg:col-span-2">
            <h3 className="text-xl font-bold text-text-primary mb-2">
              {t('home.newsletter_title')}
            </h3>
            <p className="text-sm text-text-secondary">
              {t('home.newsletter_desc')}
            </p>
          </div>
          <div>
            <form onSubmit={handleSubscribe} className="flex relative">
              <input
                type="email"
                placeholder={t('home.newsletter_placeholder')}
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className={`
                  w-full px-4 py-3 rounded-lg border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50
                  ${status === 'invalid' ? 'border-danger' : 'border-border'}
                `}
              />
              <button
                type="submit"
                className="absolute right-1 top-1 bottom-1 px-4 bg-accent hover:bg-accent-hover text-white text-xs font-semibold rounded-md transition-colors"
              >
                {t('home.newsletter_btn')}
              </button>
            </form>
          </div>
        </div>
      </div>

      {/* Main Footer Links */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div>
            <h2 className="text-2xl font-black text-accent mb-4">NUVARA</h2>
            <p className="text-sm text-text-secondary leading-relaxed mb-6">
              {t('footer.about_desc')}
            </p>
            <div className="flex space-x-4 rtl:space-x-reverse text-text-secondary">
              <a href="#" className="hover:text-accent transition-colors"><FacebookIcon /></a>
              <a href="#" className="hover:text-accent transition-colors"><InstagramIcon /></a>
              <a href="#" className="hover:text-accent transition-colors"><TwitterIcon /></a>
            </div>
          </div>

          <div>
            <h4 className="text-sm font-bold text-text-primary uppercase tracking-wider mb-4">
              {t('footer.customer_service')}
            </h4>
            <ul className="space-y-2.5 text-sm text-text-secondary">
              <li><a href="#" className="hover:text-accent transition-colors">{t('footer.faq')}</a></li>
              <li><a href="#" className="hover:text-accent transition-colors">{t('footer.returns')}</a></li>
              <li><a href="#" className="hover:text-accent transition-colors">Shipping Info</a></li>
              <li><a href="#" className="hover:text-accent transition-colors">Order Tracking</a></li>
            </ul>
          </div>

          <div>
            <h4 className="text-sm font-bold text-text-primary uppercase tracking-wider mb-4">
              Company
            </h4>
            <ul className="space-y-2.5 text-sm text-text-secondary">
              <li><a href="#" className="hover:text-accent transition-colors">{t('footer.about_us')}</a></li>
              <li><a href="#" className="hover:text-accent transition-colors">Careers</a></li>
              <li><a href="#" className="hover:text-accent transition-colors">Sustainability</a></li>
              <li><a href="#" className="hover:text-accent transition-colors">{t('footer.contact_us')}</a></li>
            </ul>
          </div>

          <div>
            <h4 className="text-sm font-bold text-text-primary uppercase tracking-wider mb-4">
              Payment & Security
            </h4>
            <p className="text-sm text-text-secondary mb-4 flex items-center">
              <ShieldCheck className="w-4 h-4 text-green-500 mr-2 rtl:ml-2 rtl:mr-0" />
              PCI-DSS Compliant Gateway
            </p>
            <div className="flex flex-wrap gap-2 text-xs font-semibold text-text-secondary">
              <span className="px-2 py-1 bg-bg-primary border border-border rounded">Visa</span>
              <span className="px-2 py-1 bg-bg-primary border border-border rounded">Mastercard</span>
              <span className="px-2 py-1 bg-bg-primary border border-border rounded">Stripe</span>
              <span className="px-2 py-1 bg-bg-primary border border-border rounded">bKash</span>
            </div>
          </div>
        </div>
      </div>

      {/* Bottom Bar */}
      <div className="bg-bg-primary py-6 transition-colors border-t border-border">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center text-xs text-text-secondary">
          <p>© {new Date().getFullYear()} Nuvara. {t('footer.rights')}</p>
          <div className="flex space-x-6 rtl:space-x-reverse mt-4 md:mt-0">
            <a href="#" className="hover:text-accent">{t('footer.privacy')}</a>
            <a href="#" className="hover:text-accent">{t('footer.terms')}</a>
          </div>
        </div>
      </div>
    </footer>
  );
};
