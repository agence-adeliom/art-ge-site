import React, {useState, useEffect} from "react";

import {allSrc} from '@components/Thematique/ImagesSrc';
import PlaceholderCards from "@images/placeholder-cards.svg";

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

const Images = ({slug, ...props}: {
    slug: any,
    class: string
}) => {
    const [src, setSrc] = useState(PlaceholderCards)
    useEffect(() => {
        slug == biodiversity ? 
        setSrc(allSrc.backgroundBiodiversity)
        : slug == trash ?
        setSrc(allSrc.backgroundTrash)
        : slug == water ? 
        setSrc(allSrc.backgroundWater)
        : slug == building ? 
        setSrc(allSrc.backgroundEcoBuilding)
        : slug == energy ? 
        setSrc(allSrc.backgroundEnergy)
        : slug == cleaning ? 
        setSrc(allSrc.backgroundCleaning)
        : slug == mobility ? 
        setSrc(allSrc.backgroundMobility)
        : slug == handicap ? 
        setSrc(allSrc.backgroundHandicap)
        : slug == social ? 
        setSrc(allSrc.backgroundInclusivity)
        : slug == sensibility ? 
        setSrc(allSrc.backgroundSensibility)
        : slug == team ? 
        setSrc(allSrc.backgroundTeam)
        : slug == economy ? 
        setSrc(allSrc.backgroundEconomy)
        : slug == local ? 
        setSrc(allSrc.backgroundCoop)
        : slug == culture ? 
        setSrc(allSrc.backgroundCulture)
        : slug == label ? 
        setSrc(allSrc.backgroundLabel)
        : allSrc.backgroundBiodiversity
    }, [])

    return ( 
        <>
            <img src={src} alt={slug} className={props.class}></img>
        </>
    )
}

export default Images;