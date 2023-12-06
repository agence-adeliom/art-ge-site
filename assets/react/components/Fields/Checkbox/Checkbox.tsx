import React, { FunctionComponent } from 'react';
import { useController } from 'react-hook-form';
import { Error } from '@components/Fields/Error';
import { cx } from 'class-variance-authority';
import { Icon } from '@components/Typography/Icon';
import { CheckboxProps } from '@components/Fields/Checkbox';
import { Fields } from '@react/types/Fields';

export const Checkbox: FunctionComponent<CheckboxProps> = ({
  name,
  control,
  defaultValue = '',
  required = false,
  disabled = false,
  value,
  children,
}) => {
  const { field, fieldState } = useController({ name, control, defaultValue });

  return (
    <div className="flex flex-col items-start gap-2 mt-8">
      <label className="flex items-start gap-4">
        <span
          className={cx(
            'flex items-center justify-center flex-shrink-0 text-primary-600 w-6 h-6 border-neutral-400 border focus:ring-1 focus:ring-primary-600 cursor-pointer transition-colors',
            field.value && 'text-white bg-primary-600 border-primary-600',
          )}
        >
          {field.value && <Icon icon="fa-check" />}
        </span>
        <input
          type="checkbox"
          className="hidden"
          name={name}
          defaultChecked={!!defaultValue}
          onClick={() => field.onChange(!field.value)}
          value={value}
        />

        <span>{children}</span>
      </label>
      {fieldState.error && <Error message={fieldState.error.message!} />}
    </div>
  );
};
