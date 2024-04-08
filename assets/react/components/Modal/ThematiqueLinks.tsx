import React from "react";
import { Icon } from '@components/Typography/Icon';
import { Text } from '@components/Typography/Text';
import {ScoreLink} from "@screens/Resultats";

const ThematiqueLinks = ({ links }: {
    links: ScoreLink[],
}) => {

    return links.length > 0 &&
    <div className="bg-neutral-100 p-4 mt-4">
        <Text weight={600}>Pour aller plus loin...</Text>
        <div className="flex flex-col gap-2 mt-2">
            {links.map((link, index) => (
                <a href={link.link} key={index} className="flex gap-2 items-center group" target={'_blank'} rel={"noopener"}>
                    <Icon icon={link.type === 'doc' ? 'fa-file' : (link.type === 'video' ? 'fa-circle-play' : 'fa-arrow-up-right-from-square')} color="primary-600" className="group-hover:text-tertiary-800 trans-default"></Icon>
                    <Text weight={600} color="primary-600" className="group-hover:text-primary-800 trans-default" dangerouslySetInnerHTML={{__html:link.label}}></Text>
                </a>
            ))}
        </div>
    </div>
}

export default ThematiqueLinks
