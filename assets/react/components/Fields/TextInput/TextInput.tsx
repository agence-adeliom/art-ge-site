import React, { ReactElement, FunctionComponent } from 'react';
import { TextInputProps } from '@components/Fields/TextInput';
import { Fields } from '@react/types/Fields';
import { useController } from 'react-hook-form';
import { cx } from 'class-variance-authority';
import { Error } from '@components/Fields/Error';

const inputClass: string = 'border-0 border-b border-neutral-500 block w-full mt-4 pb-2 ring-0 outline-none focus:ring-0 focus:border-secondary-200 trans-default';
export const TextInput: FunctionComponent<TextInputProps> = ({
  label,
  name,
  placeholder,
  type = Fields.TEXT,
  control,
  defaultValue = '',
  required = false,
  disabled = false,
  containerClass,
  autoCompleteChoice,
}) => {
  const { field, fieldState } = useController({ name, control, defaultValue });

  return (
    <div className={containerClass}>
      {label && (
        <label className={cx('mb-2')} htmlFor={name}>
          {label}
        </label>
      )}

      <input
        
        className={inputClass}
        placeholder={placeholder}
        type={type}
        id={name}
        autoComplete={autoCompleteChoice === false ? 'off' : 'on' }
        aria-invalid={fieldState.error ? 'true' : 'false'}
        //onChange={(event) => handleChange(event)}
        {...field}
        
      ></input>
      {fieldState.error && <Error message={fieldState.error.message!} />}
    </div>
  );
};
