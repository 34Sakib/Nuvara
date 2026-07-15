import React from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { CheckCircle, AlertCircle, Info, X } from 'lucide-react';
import { useToastStore } from '../../store/toastStore';

export const Toast = ({ toast }) => {
  const { removeToast } = useToastStore();
  
  const icons = {
    success: <CheckCircle className="w-5 h-5 text-green-500" />,
    danger: <AlertCircle className="w-5 h-5 text-red-500" />,
    info: <Info className="w-5 h-5 text-indigo-500" />
  };

  const borderColors = {
    success: 'border-green-200 dark:border-green-900 bg-white dark:bg-green-950/20',
    danger: 'border-red-200 dark:border-red-900 bg-white dark:bg-red-950/20',
    info: 'border-indigo-200 dark:border-indigo-900 bg-white dark:bg-indigo-950/20'
  };

  return (
    <motion.div
      layout
      initial={{ opacity: 0, y: -20, scale: 0.9 }}
      animate={{ opacity: 1, y: 0, scale: 1 }}
      exit={{ opacity: 0, y: -20, scale: 0.9 }}
      className={`flex items-center justify-between p-4 mb-3 border rounded-xl shadow-lg w-80 max-w-full ${borderColors[toast.type]}`}
    >
      <div className="flex items-center space-x-3 rtl:space-x-reverse">
        {icons[toast.type]}
        <p className="text-sm font-semibold text-text-primary">{toast.message}</p>
      </div>
      <button
        onClick={() => removeToast(toast.id)}
        className="text-text-secondary hover:text-text-primary p-0.5 rounded transition-colors"
      >
        <X className="w-4 h-4" />
      </button>
    </motion.div>
  );
};

export const ToastContainer = () => {
  const { toasts } = useToastStore();

  return (
    <div className="fixed top-6 right-6 rtl:left-6 rtl:right-auto z-50 flex flex-col items-end rtl:items-start max-w-[calc(100vw-3rem)]">
      <AnimatePresence>
        {toasts.map((toast) => (
          <Toast key={toast.id} toast={toast} />
        ))}
      </AnimatePresence>
    </div>
  );
};
