import React from "react";
import { Button } from "@components/Action/Button";
import { Heading } from "@components/Typography/Heading";
import { Text } from "@components/Typography/Text";
import Leaf from "@icones/leafs.svg"

const Cta = () => {
    return (
        <div className="bg-primary-600 px-2 py-6 lg:p-12 relative overflow-hidden">
            <img src={Leaf} className="absolute -bottom-14 -right-20 w-72 aspect-square" alt="icone de feuille"></img>
            <div className="flex flex-col md:flex-row gap-6 md:items-center container">
                <div className="flex flex-col gap-2">
                    <Heading color="white" variant="display-3">Et si vous alliez plus loin ? </Heading>
                    <Text color="white">Nous vous accompagnons dans votre transition durable, découvrez tous les outils mis à votre disposition pour passer à l’action.</Text>
                </div>
                <div className="w-fit md:ml-auto">
                    <Button href={"https://www.art-grandest.fr/"} target={"_blank"} rel={"noopener"} icon="fa-minus" iconSide="left" variant="secondary" className="whitespace-nowrap">Nous contacter</Button>
                </div>
            </div>

        </div>
        )
}
export default Cta
