import React from "react"
import { motion, AnimatePresence } from "framer-motion"

const modalClassName = "max-md:left-1/2 max-md:-translate-x-1/2  w-[90%] absolute top-[calc(100%-20px)] right-0 bg-white p-6 z-[110]  md:w-[400px] rounded border-2 border-neutral-200 z-[10000]"
const backdrop = "fixed top-0 left-0 w-screen h-screen bg-black bg-opacity-50 z-[100]"

export const QuitForm = ({ isVisible, children } : {
    isVisible: boolean,
    children: React.ReactNode
}

) => (
    <AnimatePresence >
      {isVisible && ( 
        <motion.div   
          className={'absolute z-[1000] w-full h-full top-0 left-0'}
          initial={{  opacity: 0 }}
          animate={{  opacity: 1 }}
          exit={{ opacity: 0 }} 
        >
            <div className={modalClassName}>
                {children}
            </div>
            <div className={backdrop}></div>
            
        </motion.div>
       )} 
     
    </AnimatePresence>
  )