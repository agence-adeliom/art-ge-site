import React, { ReactNode } from 'react';
import { motion } from 'framer-motion';

export const StepAnim = ({ children }: { children: ReactNode }) => (
  <motion.div
    key={'step'}
    className="md:py-6"
    initial={{ y: 20, opacity: 0 }}
    animate={{ y: 0, opacity: 1 }}
    exit={{ y: -20, opacity: 0 }}
  >
    {children}
  </motion.div>
);
