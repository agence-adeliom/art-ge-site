
import React from "react"
import { Icon } from "@components/Typography/Icon"
const parentClass= "w-fit font-semibold flex-shrink-0 py-2 text-sm font-normal overflow-hidden trans-default flex items-center gap-2 cursor-pointer relative text-neutral-700 lg:group-hover:text-white"
const afterClass= "max-lg:after:hidden after:-translate-x-[calc(100%+4px)] after:transition-all after:duration-300 group-hover:after:translate-x-0 after:absolute after:w-full after:bottom-[2px] after:left-0 after:h-[1px] after:block after:bg-white"
const LinkTab = ({onClickFunction, label } : {
    onClickFunction?: Function,
    label: string,
}) => {
    return (
        <div className="col-span-2 relative h-full flex items-center">
            <div className={`${parentClass} ${afterClass}`} 
                {...(onClickFunction && {onClick: event => onClickFunction(event)})}>
                <div className="relative z-10">{label} <Icon size="sm" icon={`fa-solid fa-chevron-right`}></Icon></div>
            </div>
            <div className="hidden lg:block group-hover:translate-x-0 translate-x-full trans-default bg-primary-600 w-[calc(((100vw-320px)/11)*2+10px)] h-[calc(100%+24px)] absolute -top-3 -left-3"></div>

        </div>
    )
}

export default LinkTab