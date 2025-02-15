import React from "react";
import { QuitForm } from '@components/Animation/QuitForm';
import { Text } from '@components/Typography/Text';
import { Button } from '@components/Action/Button';
import { Icon } from "@components/Typography/Icon";

const NoDataModal = ( { closeModal }: { 
    closeModal: Function, 
}) => {
    const backdrop = "fixed top-0 left-0 w-screen h-screen bg-black bg-opacity-50 z-[100]"
    return (
        <>
        <div className={backdrop} onClick={() => (closeModal())}></div>
        <QuitForm isVisible>
            <div className="flex flex-col gap-5">
                <div className="flex gap-3 items-baseline">
                    <Text size="2xl" className="font-title">Il n’existe pas encore de résultat pour votre recherche</Text>
                    <div
                        className="w-fit h-fit"
                        onClick={() => closeModal()}>
                        <Icon 
                            size="xl" 
                            className="text-neutral-600 cursor-pointer" 
                            icon="fa-xmark"
                            >
                        </Icon>
                    </div>
                </div>
                
                <Text weight={400} color="neutral-700">Nos données s'enrichissent de jour en jour. Nous vous invitons à formuler votre requête ultérieurement.</Text>
                <div className="flex gap-3 flex-wrap">
                    <Button onClick={() => closeModal()} className="w-full sm:w-fit" variant="secondary">J'ai compris</Button>
                </div>
            </div>
        </QuitForm>
        </>
        
    )
}
export default NoDataModal