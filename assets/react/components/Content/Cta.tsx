import React from "react";
import { Button } from "@components/Action/Button";
import { Heading } from "@components/Typography/Heading";
import { Text } from "@components/Typography/Text";

const Cta = () => {
    return (
        <div className="bg-primary-600 p-10">
            <div className="flex flex-col md:flex-row gap-6 md:items-center container">
                <div className="flex flex-col gap-2">
                    <Heading color="white" variant="display-3">Et si vous alliez plus loin ? </Heading>
                    <Text color="white">Progressez en matière d'éco-responsabilité grâce à notre accompagnement personnalisé.</Text>
                </div>
                <div className="w-fit md:ml-auto">
                    <Button icon="fa-minus" iconSide="left" variant="secondary" className="whitespace-nowrap">Nous contacter</Button>
                </div>
            </div>

        </div>
        )
}
export default Cta