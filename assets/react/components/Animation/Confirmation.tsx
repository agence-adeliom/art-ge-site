import React from "react"
import { motion, AnimatePresence } from "framer-motion"



export const ConfirmationAnim = ({ isVisible, children } : {
    isVisible: boolean,
    children: React.ReactNode
}

) => (
    <AnimatePresence >
      {isVisible && ( 
        <motion.div   
          className="md:py-10"
          initial={{  opacity: 0 }}
          animate={{  opacity: 1 }}
          exit={{ opacity: 0, display: 'none' }} 
        >
            {children}
        </motion.div>
       )} 
     
    </AnimatePresence>
  )