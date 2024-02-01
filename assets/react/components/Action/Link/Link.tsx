
import React from "react"
import { Icon } from "@components/Typography/Icon"
const parentClass= "w-fit flex-shrink-0 py-2 text-sm font-normal overflow-hidden flex items-center gap-2 cursor-pointer relative after:-translate-x-full after:transition-all trans-default hover:after:translate-x-0 after:absolute after:w-full after:bottom-0 after:left-0 after:h-[1px] after:block after:bg-neutral-700 text-neutral-700"
const Link = ({onClickFunction, label} : {
    onClickFunction?: Function,
    label: string
}) => {
    return (
        <div className={parentClass} 
        {...(onClickFunction && {onClick: event => onClickFunction(event)})}>
            {label} <Icon size="sm" icon="fa-solid fa-chevron-right"></Icon>
        </div>
    )
}

export default Link