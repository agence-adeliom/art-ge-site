import React from "react";

const YesNoCard = ({className, id, handleChoice, choice} : {
    className: string,
    id: string,
    handleChoice: Function,
    choice: string
}) => {

    const handleClick = (event : any) => {
        console.log(event.target.value)
        if (event.target.value === 'yes') {
            handleChoice('true')
        } else {
            handleChoice('false')
        }
        
    }
    return (
        <div className="grid gap-6 grid-cols-2">
            <label htmlFor={`${id}-yes`} className={`${choice === 'true' && 'is-active'} ${className} `} onClick={event => handleClick(event)}>
                
                    <input type="radio" value="yes" id={`${id}-yes`} name={id} className={`radio`} />
                    <span>Oui</span>
               
            </label>

            <label htmlFor={`${id}-no`} className={`${choice === 'false' && 'is-active'} ${className} `} onClick={event => handleClick(event)}>
                
                    <input type="radio" value="no" id={`${id}-no`} name={id} className={`radio`} />
                    <span>Non</span>
               
            </label>
        </div>
        
    )
}

export default YesNoCard