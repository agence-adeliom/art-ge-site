import React from "react";
import { QuitForm } from '@components/Animation/QuitForm';
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import { Button } from '@components/Action/Button';
import { Icon } from "@components/Typography/Icon";
import { Link } from "react-router-dom";

const QuitFormModal = ( { closeModal }: { 
    closeModal: Function, 
}) => {

    return (
        <QuitForm isVisible>
            <div className="flex flex-col gap-5">
                <div className="flex gap-3 items-baseline">
                    <Text size="2xl" className="font-title">Êtes-vous sûr de vouloir nous quitter ?</Text>
                    <div
                        className="w-fit h-fit"
                        onClick={() => closeModal()}>
                        <Icon 
                            size="xl" 
                            className="text-neutral-600" 
                            icon="fa-xmark"
                            >
                        </Icon>
                    </div>
                </div>
                
                <Text weight={400} color="neutral-700">Vos réponses ne seront pas sauvegardées.</Text>
                <div className="flex gap-3 flex-wrap">
                    <Link to="/" className="w-full sm:w-fit">
                        <Button  variant="primary">Oui, quitter</Button>
                    </Link>
                    <Button onClick={() => closeModal()} className="w-full sm:w-fit" variant="secondary">Annuler</Button>
                </div>
            </div>
        </QuitForm>
    )
}
export default QuitFormModal