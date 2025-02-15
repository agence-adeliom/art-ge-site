import React, { FunctionComponent, MutableRefObject, useRef, useEffect } from 'react';
import { AccordionProps } from '@components/Accordion/Accordion.types';
import { Icon } from '@components/Typography/Icon';
import { Text } from '@components/Typography/Text';
import {Choice} from "@screens/Resultats";

export const Accordion: FunctionComponent<AccordionProps> = ({
  question,
  choices,
  isOpen,
  handleClick,
}) => {
  const content: MutableRefObject<HTMLDivElement | null> = useRef(null);

  return (
    <div className=" border-neutral-300 border-b py-4">
      <div
        role="button"
        aria-expanded={isOpen}
        onClick={handleClick}
        className="group flex items-start justify-between"
      >
        <Text
          size="base"
          weight={500}
          color={`${isOpen ? 'secondary-800' : 'neutral-700'}`}
          className="max-w-[90%] lg:group-hover:text-secondary-800 flex items-center gap-2 group"
        >
          {question} <span className={`${isOpen ? 'bg-secondary-800' : 'bg-neutral-700'} lg:group-hover:bg-secondary-800 rounded-full text-white font-light px-2 py-1 text-xs trans-default`}>{choices.length}</span>
        </Text>
        <Icon
          icon="fa-chevron-down"
          variant="solid"
          className={`transition-default mt-1 origin-[50%_55%] ${
            isOpen ? '-rotate-180' : ''
          }`}
        />
      </div>
      <div
        ref={content}
        style={{ height: isOpen ? content?.current?.scrollHeight + 'px' : '0' }}
        className={`max-w-[90%] overflow-hidden transition-[height] duration-200 ease-in-out ${
          !isOpen ? 'pointer-events-none h-0' : 'opacity-1'
        }`}
      >
        <ul className="list-disc  mt-4 list-inside marker:text-secondary-800 flex flex-col gap-2">
          {choices.map((choice: Choice) => <li className="text-sm" key={choice.slug} dangerouslySetInnerHTML={{ __html: choice.name }}></li>)}
        </ul>

      </div>
    </div>
  );
};
