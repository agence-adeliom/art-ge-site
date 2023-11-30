import React, {useState} from 'react';
import { Heading } from '@components/Typography/Heading'
import { Text } from '@components/Typography/Text'
import { Button } from '@components/Action/Button'
import Input from '@components/Forms/Input'

const StepFour = ( {handleChange, address, establishmentName, city, zipCode, nextStep} : {
    handleChange: Function,
    address: string,
    establishmentName: string,
    city: string,
    zipCode: string,
    nextStep: Function
}) => {
    return (
        <>
            <Heading variant="display-4">L’adresse de votre établissement...</Heading>
            <Text className="mt-6" color="neutral-500" weight={400} size="sm">Ces coordonnées nous permettent de vous situer dans le Grand Est.</Text>
            <div className="flex-wrap md:flex-nowrap flex gap-6 w-full mt-8"> 
                <Input 
                    containerClass="w-full md:w-1/2" 
                    label={{className:"block", name: "Établissement"}}
                    id="establishmentName"
                    input={{type: 'text', className:"block", value: establishmentName, handleChange: handleChange, placeHolder: "Nom de l’établissement"}}
                ></Input>
                <Input 
                    containerClass="w-full md:w-1/2" 
                    label={{className:"block", name: "Adresse"}}
                    id="address"
                    input={{type: 'text', className:"block", value: address, handleChange: handleChange, placeHolder: "Ex : 8 rue de l'école"}}
                ></Input>
            </div>
            <div className="flex-wrap md:flex-nowrap flex gap-6 w-full mt-8"> 
                <Input 
                    containerClass="w-full md:w-1/2" 
                    label={{className:"block", name: "Code postal"}}
                    id="zipCode"
                    input={{type: 'text', className:"block", value: zipCode, handleChange: handleChange, placeHolder: "Ex : 67000"}}
                ></Input>
                <Input 
                    containerClass="w-full md:w-1/2" 
                    label={{className:"block", name: "Ville"}}
                    id="city"
                    input={{type: 'text', className:"block", value: city, handleChange: handleChange, placeHolder: "Ex : Strasbourg"}}
                ></Input>
            </div>

             
            
            <Button 
            size="lg" 
            className="mt-8"
            icon="fa-minus"
            iconSide="left"
            disabled={establishmentName === '' || address === '' || zipCode === '' || city === '' ? true : false} 
            onClick={(event) => {event.preventDefault(); nextStep()}}
            >
                Suivant
            </Button> 
        </>
    )
}

export default StepFour