import React, { FunctionComponent } from 'react';
import { ChoiceCardsProps } from '@components/Fields/ChoiceCard';
import { cx } from 'class-variance-authority';

const cardClass =
  'p-4 cursor-pointer flex gap-4 flex items-center border border-neutral-200 group trans-default lg:hover:bg-tertiary-200 is-active:border-primary-600 is-active:bg-primary-50';

export const ChoiceCard: FunctionComponent<ChoiceCardsProps> = ({
  className,
  icon,
  children,
  isActive,
  onClick,
  ...props
}) => {
  const { iconSrc, alt } = icon;
  return (
    <div
      className={cx(cardClass, isActive && 'is-active')}
      onClick={onClick}
      {...props}
    >
      <div
        className={
          'bg-secondary-50 group-hover:bg-tertiary-400 trans-default is-active:bg-primary-200 iconClass pointer-events-none'
        }
      >
        <img src={iconSrc} alt={alt}></img>
      </div>
      <div className="pointer-events-none">{children}</div>
    </div>
  );
};
