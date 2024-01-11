import React from "react"
import { motion, AnimatePresence } from "framer-motion"

const modalClassName = "left-1/2 -translate-x-1/2 w-[90%] absolute right-0 bg-white p-6 z-[110]  top-1/2 -translate-y-1/2 md:w-[400px] rounded border-2 border-neutral-200 z-[10000]"

export const QuitForm = ({ isVisible, children } : {
    isVisible: boolean,
    children: React.ReactNode
}

) => (
    <AnimatePresence >
      {isVisible && ( 
        <motion.div   
          className={modalClassName}
          initial={{  opacity: 0 }}
          animate={{  opacity: 1 }}
          exit={{ opacity: 0 }} 
        >
            <div className={'modalClassName'}>
                {children}
            </div>
            
        </motion.div>
       )} 
     
    </AnimatePresence>
  )