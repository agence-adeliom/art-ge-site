import React from "react"
import backgroundBiodiversity from '@images/background-biodiversity.jpeg';
import backgroundEcoBuilding from '@images/background-ecoconstruction.jpeg';
import backgroundMobility from '@images/background-mobility.jpeg';
import backgroundInclusivity from '@images/background-inclusivity.jpeg';
import backgroundSensibility from '@images/background-sensibility.jpeg';
import backgroundEconomy from '@images/background-economy.jpeg';
import backgroundCoop from '@images/background-coop.jpeg';
import backgroundCulture from '@images/background-culture.jpeg';
import backgroundWater from '@images/background-water.jpeg';


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
        <div className="bg-neutral-600 max-lg:h-32 mobileLeftBleed lg:left-0 max-lg:order-first lg:col-start-9 lg:col-span-4 containerBleed relative">
            <img src={backgroundBiodiversity}
            className={`${
                thematique === biodiversity ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={'https://media.istockphoto.com/id/1273367579/fr/photo/homme-de-race-m%C3%A9lang%C3%A9-gai-regardant-loin-tout-en-collectant-la-poubelle-avec-des-amis.jpg?s=1024x1024&w=is&k=20&c=JDAliPd_QkWEb8G2DswjhtIx27yjUDGhls6lu_8JBO4='}
            className={`${
                thematique === trash ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={backgroundWater}
            className={`${
                thematique === water ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={backgroundEcoBuilding}
            className={`${
                thematique === building ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={'https://media.istockphoto.com/id/1343774494/fr/photo/un-lac-en-forme-de-bulbe-au-milieu-dune-for%C3%AAt-luxuriante-symbolisant-des-id%C3%A9es-fra%C3%AEches-de.jpg?s=1024x1024&w=is&k=20&c=DKks5Iq4iL8F6bfOBCMhk9O3EgpBoUO1xjNkauFpnVs='}
            className={`${
                thematique === energy ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={'https://media.istockphoto.com/id/1345347776/fr/photo/une-femme-pr%C3%A9pare-un-nettoyant-d%C3%A9vier-naturel-non-chimique-%C3%A0-la-maison-avec-du-bicarbonate.jpg?s=1024x1024&w=is&k=20&c=NhrFDViCQTHwPv7BQS_DcZd2OaxzQTsykCRobPwRmsg='}
            className={`${
                thematique === cleaning ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={backgroundMobility}
            className={`${
                thematique === mobility ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={'https://media.istockphoto.com/id/180758739/fr/photo/p%C3%A8re-aider-sa-petite-soeur-%C3%A0-mobilit%C3%A9-r%C3%A9duite-profitez-de-la-journ%C3%A9e.jpg?s=1024x1024&w=is&k=20&c=FvzW2F6QZ0GTidhmp8o7-AC6JG-vdUlby29k7vadGcI='}
            className={`${
                thematique === handicap ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={backgroundInclusivity}
            className={`${
                thematique === social ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={backgroundSensibility}
            className={`${
                thematique === sensibility ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={'https://media.istockphoto.com/id/881484382/fr/photo/jeunes-gens-travaillent-dans-les-bureaux-modernes.jpg?s=1024x1024&w=is&k=20&c=7BPv628VnBIWpN-qGT3NQotUh6nDzF0z5UxrLosIyCo='}
            className={`${
                thematique === team ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={backgroundEconomy}
            className={`${
                thematique === economy ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
             <img src={backgroundCoop}
            className={`${
                thematique === local ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
             <img src={backgroundCulture}
            className={`${
                thematique === culture ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
             <img src={backgroundBiodiversity}
            className={`${
                thematique === label ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
        </div>
    )
}
export default AsideForm