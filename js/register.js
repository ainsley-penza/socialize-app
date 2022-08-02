//fetch a JSON RESTful API called restcountries.com that contains all the countries information.
fetch("https://restcountries.com/v2/all")
.then(response => response.json())
.then(data => { 
                    //traverse through every country in the API and save it to a local variable called countryname and concentenate the innerHTML of the countries select with
                    //the current (curr) country.
                    var countriesselect = document.querySelector('#countries')
                    for(let curr=0; curr<data.length; curr++) {
                        let countryname = (data[curr].name);
                        countriesselect.innerHTML += `<option value="`+(curr+1)+`">`+countryname+`</option>`;
                    };
              });