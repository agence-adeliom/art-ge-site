import React, {useState} from 'react';
import { Heading } from '@components/Typography/Heading'
import { Text } from '@components/Typography/Text'
import { Button } from '@components/Action/Button'
import Input from '@components/Forms/Input'

const StepOne = ({handleChange, handleSubmit, acceptLegal, firstname, lastname, email, tel, inputClass, legalChecked} : {
    handleChange: Function,
    handleSubmit: Function,
    acceptLegal: Function,
    firstname: string,
    lastname: string,
    email: string,
    tel: string,
    inputClass: string,
    legalChecked: boolean
}) => {
    return (
        <>
            <Heading variant="display-4">Pour commencer</Heading>
            <Text className="mt-6" color="neutral-500" weight={400} size="sm">Renseignez ces informations afin que nous puissions vous identifier.</Text>
            <div className="flex-wrap md:flex-nowrap flex gap-6 w-full mt-8"> 
                <Input 
                    containerClass="w-full md:w-1/2" 
                    label={{className:"block", name: "Prénom"}}
                    id="firstname"
                    input={{type: 'text', className:"block", value: firstname, handleChange: handleChange, placeHolder: "Ex : Julie"}}
                ></Input>
                <Input 
                    containerClass="w-full md:w-1/2" 
                    label={{className:"block", name: "Nom"}}
                    id="lastname"
                    input={{type: 'text', className:"block", value: lastname, handleChange: handleChange, placeHolder: "Ex : Dupont"}}
                ></Input>
            </div>
            <div className="flex flex-wrap md:flex-nowrap gap-6 w-full mt-8">
                 <Input 
                    containerClass="w-full md:w-1/2" 
                    label={{className:"block", name: "Téléphone"}}
                    id="tel"
                    input={{type: 'tel', className:"block", value: tel, handleChange: handleChange, placeHolder: "Ex : 0612345678"}}
                ></Input>
                 <Input 
                    containerClass="w-full md:w-1/2" 
                    label={{className:"block", name: "Email"}}
                    id="email"
                    input={{type: 'email', className:"block", value: email, handleChange: handleChange, placeHolder: "Ex : Dupont"}}
                ></Input>
            </div>

                <Input 
                    containerClass="flex flex-row-reverse items-start gap-4 mt-8" 
                    label={{className:"block", name: <Text weight={400} color="neutral-800"><label htmlFor="legal">J’accepte que mes données soient transmises à l’ART GE et à ses partenaires. Pour en savoir plus, consultez la <a href="#" className="classic-link">politique de confidentialité</a></label></Text>}}
                    id="legal"
                    input={{type: 'checkbox', className:"checkbox", handleChange: acceptLegal, value: "", placeHolder: "",}}
                ></Input>
            
            <Button 
            size="lg" 
            className="mt-8"
            icon="fa-minus"
            iconSide="left"
            disabled={firstname === '' || lastname === '' || email === '' || tel === '' || legalChecked === false ? true : false} 
            onClick={(event) => handleSubmit(event)}
            >
                Suivant
            </Button>
        </>
    )
}

export default StepOne