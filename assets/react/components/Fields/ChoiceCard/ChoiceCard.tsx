import React from "react";

export const ChoiceCard = ({className, icon, children, selectFunction, type, etablissement} : {
    className: string,
    selectFunction: Function,
    type: string,
    etablissement: string,
    icon: {
        iconSrc: string,
        alt: string,
        iconClass: string
    },
    children: React.ReactNode
}) => {
    const {iconSrc, alt, iconClass} = icon
    return (
        <div 
            className={`${className} ${etablissement === type && 'is-active'} `}
            data-type={type}
            onClick={ event => selectFunction(event)}>
            <div className={`${iconClass && iconClass} iconClass pointer-events-none`}>
                <img src={iconSrc} alt={alt}></img>
            </div>
            <div className="pointer-events-none">
                {children}
            </div>
        </div>
    )
}
