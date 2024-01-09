import React from "react"
import backgroundBiodiversity from '@images/background-biodiversity.jpeg';
import backgroundEcoBuilding from '@images/background-ecoconstruction.jpeg';
import backgroundMobility from '@images/background-mobility.jpeg';
import backgroundInclusivity from '@images/background-inclusivity.jpeg';
import backgroundSensibility from '@images/background-sensibility.jpeg';
import backgroundEconomy from '@images/background-economy.jpeg';
import backgroundCoop from '@images/background-coop.jpeg';
import backgroundCulture from '@images/background-culture.jpeg';

const AsideForm = ({thematique} : {
    thematique: number
}) => {

    return (
        <div className="bg-neutral-600 max-lg:h-32 mobileLeftBleed lg:left-0 max-lg:order-first lg:col-start-9 lg:col-span-4 containerBleed relative">
            <img src={backgroundBiodiversity}
            className={`${
                thematique === 46 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={'https://media.istockphoto.com/id/1273367579/fr/photo/homme-de-race-m%C3%A9lang%C3%A9-gai-regardant-loin-tout-en-collectant-la-poubelle-avec-des-amis.jpg?s=1024x1024&w=is&k=20&c=JDAliPd_QkWEb8G2DswjhtIx27yjUDGhls6lu_8JBO4='}
            className={`${
                thematique === 47 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>

            <img src={backgroundBiodiversity}
            className={`${
                thematique === 48 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={backgroundEcoBuilding}
            className={`${
                thematique === 49 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={'https://media.istockphoto.com/id/1365614868/fr/photo/gros-plan-dune-femme-tenant-un-compteur-d%C3%A9nergie-intelligent-dans-une-cuisine-mesurant.jpg?s=1024x1024&w=is&k=20&c=iscuuO6poGGt8rciP-aMTImfFb1U8W0ZgPOChAQzSFw='}
            className={`${
                thematique === 50 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={'https://media.istockphoto.com/id/1345347776/fr/photo/une-femme-pr%C3%A9pare-un-nettoyant-d%C3%A9vier-naturel-non-chimique-%C3%A0-la-maison-avec-du-bicarbonate.jpg?s=1024x1024&w=is&k=20&c=NhrFDViCQTHwPv7BQS_DcZd2OaxzQTsykCRobPwRmsg='}
            className={`${
                thematique === 51 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={backgroundMobility}
            className={`${
                thematique === 52 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={'https://media.istockphoto.com/id/180758739/fr/photo/p%C3%A8re-aider-sa-petite-soeur-%C3%A0-mobilit%C3%A9-r%C3%A9duite-profitez-de-la-journ%C3%A9e.jpg?s=1024x1024&w=is&k=20&c=FvzW2F6QZ0GTidhmp8o7-AC6JG-vdUlby29k7vadGcI='}
            className={`${
                thematique === 53 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={backgroundInclusivity}
            className={`${
                thematique === 54 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={backgroundSensibility}
            className={`${
                thematique === 55 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={'https://media.istockphoto.com/id/881484382/fr/photo/jeunes-gens-travaillent-dans-les-bureaux-modernes.jpg?s=1024x1024&w=is&k=20&c=7BPv628VnBIWpN-qGT3NQotUh6nDzF0z5UxrLosIyCo='}
            className={`${
                thematique === 56 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img src={backgroundEconomy}
            className={`${
                thematique === 57 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
             <img src={backgroundCoop}
            className={`${
                thematique === 58 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
             <img src={backgroundCulture}
            className={`${
                thematique === 59 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
             <img src={backgroundBiodiversity}
            className={`${
                thematique === 60 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
        </div>
    )
}
export default AsideForm