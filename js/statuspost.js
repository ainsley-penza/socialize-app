const validation = new window.JustValidate('#statusPostForm', {
    errorFieldCssClass: 'is-invalid',
    errorFieldStyle: {
      border: '1px solid red',
    },
    errorLabelCssClass: 'is-label-invalid',
    errorLabelStyle: {
      color: 'red',
      textDecoration: 'underlined',
    },
    successFieldCssClass: 'just-validate-success-field',
    successLabelCssClass: 'just-validate-success-label',
    focusInvalidField: true,
    lockForm: false,
    successMessage: 'Success Message',
    tooltip: {
      position: 'top',
    },
  });

  validation
  .addField('#postContent', [
    {
      rule: 'required',
      errorMessage: 'Text is required.',
    },
    {
      rule: 'maxLength',
      value: 500,
      errorMessage: 'Text exceeded 500 character limit.',
    },
  ])
  .addField('#postImage', [
    {
      rule: 'maxFilesCount',
      value: 1,
      errorMessage: 'Only 1 file is allowed.',
    },
    {
      rule: 'files',
      errorMessage: 'Invalid file types.',
      value: {
        files: {
          extensions: ['jpeg', 'png', 'jpg'],
          types: ['image/jpeg', 'image/png', 'image/jpg'],
        },
      },
    },
  ])
  .onSuccess((e) => {
    document.getElementById("statusPostForm").submit();
  });