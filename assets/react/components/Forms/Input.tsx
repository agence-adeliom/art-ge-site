import React, { Component } from "react"
import { ReactElement } from "react"

const Input = ({containerClass, label, id, input} : {
    containerClass: string,
    id: string,
    label: {
        className: string,
        name: ReactElement | string
    },
    input: {
        type: string,
        className: string,
        value: string,
        handleChange: Function,
        placeHolder: string
    }
}
) => {
    const inputClass : string = 'border-0 border-b border-neutral-500 block w-full mt-4 pb-2 focus:ring-0 focus:border-secondary-200 trans-default'


    const {className, name} = label
    const {value, type, handleChange, placeHolder, className : inputCustomClass } = input

    return (
        <div className={containerClass}>
            <label className={className && className} htmlFor={id && id}>{name}</label>
            <input className={`${type != 'checkbox' && inputClass} ${inputCustomClass} && ${inputCustomClass}`} placeholder={placeHolder && placeHolder} type={type && type} id={id && id} value={value && value} onChange={handleChange && (event => handleChange(event))}></input>
        </div>
    )
}

export default Input