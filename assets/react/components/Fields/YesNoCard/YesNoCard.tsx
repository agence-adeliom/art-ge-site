import { cx } from 'class-variance-authority';
import React, { FunctionComponent, useEffect, useState } from 'react';
import { YesNoCardProps } from '@components/Fields/YesNoCard';
import { useController } from 'react-hook-form';

const cardClassName =
  'col-span-1 w-full p-4 cursor-pointer flex gap-4 flex items-center border border-neutral-200 group trans-default lg:hover:bg-tertiary-200 is-active:border-primary-600 is-active:bg-primary-50 peer';

export const YesNoCard: FunctionComponent<YesNoCardProps> = ({
  name,
  control,
  defaultValue = '',
}) => {
  const { field, fieldState } = useController({ name, control, defaultValue });
  const handleClick = (value: boolean) => {
    setEnabled(value);
    setInteracted(true);
  };

  const [enabled, setEnabled] = useState<boolean>();
  const [interacted, setInteracted] = useState<boolean>();

  return (
    <div className="gap-6 grid-cols-2 grid ">
      <label
        htmlFor={`${name}-yes`}
        className={cx(cardClassName, enabled && interacted && 'is-active')}
        onClick={() => handleClick(true)}
      >
        <input
          type="radio"
          id={`${name}-yes`}
          className={`radio`}
          {...field}
          value={1}
        />
        <span>Oui</span>
      </label>

      <label
        htmlFor={`${name}-no`}
        className={cx(cardClassName, !enabled && interacted && 'is-active')}
        onClick={() => handleClick(false)}
      >
        <input
          type="radio"
          id={`${name}-no`}
          className={`radio`}
          {...field}
          value={0}
        />
        <span>Non</span>
      </label>
    </div>
  );
};
