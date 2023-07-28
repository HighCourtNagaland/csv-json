# Convert CSV to JSON

**eCourts Nagaland**

* Upload CSV (.csv) file with a header
* Convert & Download Json (.json)

_sample csv file_
```
"case_id","case_type","case_fullname"
"32","1","Civil Suit"
"18","2","Police Report Case"

```

_Json file output_

```
[
    {
        "case_id": "32",
        "case_type": "1",
        "case_fullname": "Civil Suit"
    },
    {
        "case_id": "18",
        "case_type": "2",
        "case_fullname": "Police Report Case"
    }
]

```
