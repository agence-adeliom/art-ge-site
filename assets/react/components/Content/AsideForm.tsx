import React from "react"
import {allSrc} from '@components/Thematique/ImagesSrc';

const biodiversity = "biodiversite-et-conservation-de-la-nature-sur-site"
const trash = "gestion-des-dechets"
const water = "gestion-de-l-eau-et-de-l-erosion"
const building = "eco-construction"
const energy = "gestion-de-l-energie"
const cleaning = "entretien-et-proprete"
const mobility = "transport-et-mobilite"
const handicap = "acces-aux-personnes-en-situation-de-handicap"
const social = "inclusivite-sociale"
const sensibility = "sensibilisation-des-acteurs"
const team = "bien-etre-de-l-equipe"
const economy = "developpement-economique-local"
const local = "cooperation-locale-et-liens-avec-les-habitants"
const culture = "culture-et-patrimoine"
const label = "labels"



const AsideForm = ({thematique} : {
    thematique: string
}) => {
    return (
        <div className="bg-neutral-600 max-lg:h-[200px] mobileLeftBleed lg:left-0 max-lg:order-first lg:col-start-9 lg:col-span-4 containerBleed relative">
            <img src={allSrc.backgroundBiodiversity}
            alt={biodiversity}
            className={`${
                thematique === biodiversity ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={allSrc.backgroundTrash}
            alt={trash}
            className={`${
                thematique === trash ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={allSrc.backgroundWater}
            alt={water}
            className={`${
                thematique === water ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={allSrc.backgroundEcoBuilding}
            alt={building}
            className={`${
                thematique === building ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={allSrc.backgroundEnergy}
            alt={energy}
            className={`${
                thematique === energy ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={allSrc.backgroundCleaning}
            alt={cleaning}
            className={`${
                thematique === cleaning ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={allSrc.backgroundMobility}
            alt={mobility}
            className={`${
                thematique === mobility ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={allSrc.backgroundHandicap}
            alt={handicap}
            className={`${
                thematique === handicap ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={allSrc.backgroundInclusivity}
            alt={social}
            className={`${
                thematique === social ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={allSrc.backgroundSensibility}
            alt={sensibility}
            className={`${
                thematique === sensibility ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={allSrc.backgroundTeam} 
            alt={team}
            className={`${
                thematique === team ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={allSrc.backgroundEconomy}
            alt={economy}
            className={`${
                thematique === economy ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={allSrc.backgroundCoop}
            alt={local}
            className={`${
                thematique === local ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={allSrc.backgroundCulture}
            alt={culture}
            className={`${
                thematique === culture ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={allSrc.backgroundLabel}
            alt={label}
            className={`${
                thematique === label ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

        </div>
    )
}
export default AsideForm