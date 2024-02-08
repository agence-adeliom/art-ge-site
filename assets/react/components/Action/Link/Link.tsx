
import React from "react"
import { Icon } from "@components/Typography/Icon"
const parentClass= "w-fit font-semibold flex-shrink-0 py-2 text-sm font-normal overflow-hidden flex items-center gap-2 cursor-pointer relative after:-translate-x-[calc(100%+4px)] after:transition-all after:duration-300 trans-default hover:after:translate-x-0 after:absolute after:w-full after:bottom-[2px] after:left-0 after:h-[1px] after:block after:bg-neutral-700 text-neutral-700"
const Link = ({onClickFunction, label, icon } : {
    onClickFunction?: Function,
    label: string,
    icon: string
}) => {
    return (
        <div className={parentClass} 
        {...(onClickFunction && {onClick: event => onClickFunction(event)})}>
            {label} <Icon size="sm" icon={`fa-solid ${icon}`}></Icon>
        </div>
    )
}

export default Link