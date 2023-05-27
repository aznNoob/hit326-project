// The DOM element you wish to replace with Tagify
var input = document.querySelector('input[name=tags]');

// initialize Tagify on the above input node reference
new Tagify(input, {
    maxTags: 5,
    originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
})