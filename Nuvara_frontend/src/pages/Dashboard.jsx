import React, { useState, useEffect } from 'react';
import { useSearchParams, useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { 
  User, Package, MapPin, Heart, Settings, 
  Trash2, ShoppingCart, ChevronRight 
} from 'lucide-react';
import { useCartStore } from '../store/cartStore';
import { useLocaleStore } from '../store/localeStore';
import { useToastStore } from '../store/toastStore';
import { useAuthStore } from '../store/authStore';
import { getLocalized } from '../utils/mockData';
import { Card } from '../components/ui/Card';
import { Button } from '../components/ui/Button';
import { RatingStars } from '../components/ui/RatingStars';
import { SectionDivider } from '../components/ui/SectionDivider';

const mockPastOrders = [
  {
    id: 'NVR-381029',
    date: '2026-07-05',
    status: 'delivered',
    total: 249.99,
    itemsCount: 1,
    items: [
      { name: 'AeroSound Pro Wireless Headphones', price: 199.99, quantity: 1 }
    ]
  },
  {
    id: 'NVR-291083',
    date: '2026-06-18',
    status: 'shipped',
    total: 89.99,
    itemsCount: 1,
    items: [
      { name: 'Stride Urban Runner Sneakers', price: 89.99, quantity: 1 }
    ]
  }
];

export const Dashboard = () => {
  const { t } = useTranslation();
  const navigate = useNavigate();
  const { locale } = useLocaleStore();
  const { addToast } = useToastStore();
  const [searchParams, setSearchParams] = useSearchParams();
  const { wishlist, toggleWishlist, addToCart } = useCartStore();
  const { user, loadProfile, updateProfile } = useAuthStore();

  const activeTab = searchParams.get('tab') || 'profile';

  const [profile, setProfile] = useState({
    name: '',
    email: '',
    phone: '',
    language: locale
  });

  const [addresses, setAddresses] = useState([]);

  useEffect(() => {
    loadProfile();
  }, [loadProfile]);

  useEffect(() => {
    if (user) {
      setProfile({
        name: user.name || '',
        email: user.email || '',
        phone: user.phone || '',
        language: user.locale_preference || locale
      });
      if (user.addresses && user.addresses.length > 0) {
        setAddresses(user.addresses.map((addr) => ({
          id: addr.id,
          label: addr.label || 'Address',
          fullName: addr.fullName || user.name,
          address: addr.address,
          city: addr.city,
          state: addr.state,
          zip: addr.zip,
          country: addr.country
        })));
      } else {
        setAddresses([]);
      }
    }
  }, [user, locale]);

  const handleTabChange = (tabId) => {
    setSearchParams({ tab: tabId });
  };

  const handleProfileSubmit = async (e) => {
    e.preventDefault();
    try {
      await updateProfile({
        name: profile.name,
        email: profile.email,
        phone: profile.phone,
        locale_preference: profile.language
      });
      addToast('Profile settings updated successfully!', 'success');
    } catch (err) {
      addToast('Failed to update profile settings', 'danger');
    }
  };

  const handleWishlistAddCart = (prod) => {
    addToCart(prod, {}, 1);
    addToast(`${getLocalized(prod.name, locale)} ${t('product.cart_added')}`, 'success');
  };

  const handleWishlistRemove = (prod) => {
    toggleWishlist(prod);
    addToast(t('product.wishlist_removed'), 'info');
  };

  const activeWishlist = user?.wishlist || wishlist;
  const activeOrders = user?.orders || mockPastOrders;

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in text-left rtl:text-right">
      <div className="text-center mb-8">
        <h1 className="text-3xl font-bold font-display text-text-primary uppercase tracking-wide">
          {t('dashboard.title')}
        </h1>
        <SectionDivider />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
        
        {/* Sidebar Nav */}
        <nav className="lg:col-span-1 bg-bg-secondary border border-border rounded-2xl overflow-hidden p-2 space-y-1 transition-colors shadow-sm">
          {[
            { id: 'profile', label: t('dashboard.profile'), icon: User },
            { id: 'orders', label: t('dashboard.orders'), icon: Package },
            { id: 'addresses', label: t('dashboard.addresses'), icon: MapPin },
            { id: 'wishlist', label: t('dashboard.wishlist'), icon: Heart, count: activeWishlist.length }
          ].map((item) => {
            const Icon = item.icon;
            const isActive = activeTab === item.id;
            return (
              <button
                key={item.id}
                onClick={() => handleTabChange(item.id)}
                className={`
                  flex items-center justify-between w-full px-4 py-3 text-sm font-bold rounded-xl transition-all
                  ${isActive 
                    ? 'bg-green text-[#F5EFE4] shadow-sm' 
                    : 'text-text-secondary hover:text-text-primary hover:bg-bg-primary'
                  }
                `}
              >
                <span className="flex items-center space-x-3 rtl:space-x-reverse">
                  <Icon className="w-4 h-4" />
                  <span>{item.label}</span>
                </span>
                {item.count !== undefined && item.count > 0 && (
                  <span className={`px-2 py-0.5 rounded-full text-xs font-bold ${isActive ? 'bg-[#F5EFE4] text-green' : 'bg-bg-primary text-text-secondary border border-border'}`}>
                    {item.count}
                  </span>
                )}
              </button>
            );
          })}
        </nav>

        {/* Content Display Area (3 cols) */}
        <div className="lg:col-span-3">
          
          {/* Profile Tab */}
          {activeTab === 'profile' && (
            <Card>
              <h3 className="text-base font-bold text-text-primary uppercase tracking-wider mb-6 pb-2 border-b border-border">
                {t('dashboard.profile')}
              </h3>

              <form onSubmit={handleProfileSubmit} className="space-y-4 max-w-lg">
                <div>
                  <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">{t('checkout.full_name')}</label>
                  <input
                    type="text"
                    value={profile.name}
                    onChange={(e) => setProfile({ ...profile, name: e.target.value })}
                    className="w-full px-4 py-2.5 rounded-lg border border-border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50"
                  />
                </div>
                <div>
                  <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">{t('checkout.email')}</label>
                  <input
                    type="email"
                    value={profile.email}
                    onChange={(e) => setProfile({ ...profile, email: e.target.value })}
                    className="w-full px-4 py-2.5 rounded-lg border border-border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50"
                  />
                </div>
                <div>
                  <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">Phone Number</label>
                  <input
                    type="text"
                    value={profile.phone}
                    onChange={(e) => setProfile({ ...profile, phone: e.target.value })}
                    className="w-full px-4 py-2.5 rounded-lg border border-border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50"
                  />
                </div>
                
                <div className="pt-2">
                  <Button type="submit" variant="primary">
                    {t('dashboard.save_changes')}
                  </Button>
                </div>
              </form>
            </Card>
          )}

          {/* Orders Tab */}
          {activeTab === 'orders' && (
            <Card>
              <h3 className="text-base font-bold text-text-primary uppercase tracking-wider mb-6 pb-2 border-b border-border">
                {t('dashboard.orders')}
              </h3>

              <div className="space-y-6">
                {activeOrders.map((ord) => (
                  <div key={ord.id} className="border border-border rounded-xl p-4 space-y-4">
                    <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center text-xs font-bold text-text-secondary gap-2 border-b border-border pb-3">
                      <div>
                        {t('dashboard.order_id')} <span className="text-text-primary">#{ord.id}</span>
                      </div>
                      <div>
                        {t('dashboard.date')}: <span className="text-text-primary">{ord.date}</span>
                      </div>
                      <div>
                        {t('dashboard.status')}: <span className={`
                          px-2 py-0.5 rounded-full text-[10px] uppercase
                          ${ord.status === 'delivered' ? 'bg-green-100 text-green-700 dark:bg-green-950/20 dark:text-green-400' : 'bg-indigo-100 text-indigo-700 dark:bg-indigo-950/20 dark:text-indigo-400'}
                        `}>{ord.status}</span>
                      </div>
                    </div>

                    <div className="space-y-2">
                      {ord.items.map((item, idx) => (
                        <div key={idx} className="flex justify-between items-center text-sm font-semibold">
                          <span className="text-text-primary">{item.name} x {item.quantity}</span>
                          <span className="text-green-soft dark:text-brass-bright font-serif font-bold">${item.price}</span>
                        </div>
                      ))}
                    </div>

                    <div className="flex justify-between items-center border-t border-border pt-3 text-sm font-black text-text-primary">
                      <span>Total Paid</span>
                      <span className="text-green-soft dark:text-brass-bright font-serif font-bold">${ord.total}</span>
                    </div>
                  </div>
                ))}
              </div>
            </Card>
          )}

          {/* Addresses Tab */}
          {activeTab === 'addresses' && (
            <Card>
              <div className="flex justify-between items-center mb-6 pb-2 border-b border-border">
                <h3 className="text-base font-bold text-text-primary uppercase tracking-wider">
                  {t('dashboard.addresses')}
                </h3>
                <Button variant="secondary" size="sm">
                  {t('dashboard.add_new')}
                </Button>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {addresses.map((addr) => (
                  <div key={addr.id} className="border border-border rounded-xl p-5 bg-bg-primary/30 relative flex flex-col justify-between">
                    <div>
                      <div className="flex justify-between items-center mb-3">
                        <span className="text-xs font-bold text-brass bg-brass/5 border border-brass/15 px-2 py-0.5 rounded">
                          {addr.label}
                        </span>
                      </div>
                      <p className="text-sm font-bold text-text-primary">{addr.fullName}</p>
                      <p className="text-xs text-text-secondary mt-1">{addr.address}</p>
                      <p className="text-xs text-text-secondary">{addr.city}, {addr.state} {addr.zip}</p>
                      <p className="text-xs text-text-secondary">{addr.country}</p>
                    </div>

                    <div className="flex space-x-3.5 rtl:space-x-reverse mt-6 border-t border-border pt-3 text-xs font-bold">
                      <button className="text-brass hover:underline">{t('dashboard.edit')}</button>
                      <button className="text-red-500 hover:underline">{t('dashboard.delete')}</button>
                    </div>
                  </div>
                ))}
              </div>
            </Card>
          )}

          {/* Wishlist Tab */}
          {activeTab === 'wishlist' && (
            <Card>
              <h3 className="text-base font-bold text-text-primary uppercase tracking-wider mb-6 pb-2 border-b border-border">
                {t('dashboard.wishlist')}
              </h3>

              {activeWishlist.length > 0 ? (
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                  {activeWishlist.map((prod) => (
                    <div key={prod.id} className="border border-border rounded-xl p-4 flex space-x-4 rtl:space-x-reverse bg-bg-primary/20">
                      <img 
                        src={prod.images[0]} 
                        alt="" 
                        className="w-20 h-20 rounded-lg object-cover border border-border"
                      />
                      <div className="flex-1 flex flex-col justify-between text-left rtl:text-right">
                        <div>
                          <h4 className="font-bold text-sm text-text-primary line-clamp-1">
                            {getLocalized(prod.name, locale)}
                          </h4>
                          <span className="text-[10px] text-text-secondary font-bold uppercase">{prod.brand}</span>
                          <div className="mt-1 flex items-baseline space-x-1.5 rtl:space-x-reverse">
                            <span className="text-sm font-bold font-display text-green-soft dark:text-brass-bright">${prod.price}</span>
                          </div>
                        </div>

                        <div className="flex space-x-2 rtl:space-x-reverse mt-4">
                          <button
                            onClick={() => handleWishlistAddCart(prod)}
                            className="p-2 rounded-[4px] bg-green text-[#F5EFE4] hover:bg-green-soft active:scale-95 transition-all"
                            aria-label="Add to cart"
                          >
                            <ShoppingCart className="w-3.5 h-3.5" />
                          </button>
                          <button
                            onClick={() => handleWishlistRemove(prod)}
                            className="p-2 rounded-lg border border-border text-text-secondary hover:text-red-500 hover:bg-bg-primary transition-all"
                            aria-label="Remove wishlist"
                          >
                            <Trash2 className="w-3.5 h-3.5" />
                          </button>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              ) : (
                <div className="text-center py-12 text-text-secondary">
                  <p className="font-semibold text-sm">Your wishlist is empty.</p>
                  <p className="text-xs mt-1">Items you add to your wishlist will show up here.</p>
                </div>
              )}
            </Card>
          )}

        </div>
      </div>

    </div>
  );
};
