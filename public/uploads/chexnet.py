#!/usr/bin/env python3

import sys
import json
from random import randint

def main():
    data = {}
    data["pathologies"] = {
        "Atelectasis": {},
        "Cardiomegaly": {},
        "Effusion": {},
        "Infiltration": {},
        "Mass": {},
        "Nodule": {},
        "Pneumonia": {},
        "Consolidation": {},
        "Edema": {},
        "Emphysema": {},
        "Fibrosis": {},
        "Pleural Thickening": {},
        "Hernia": {}
    }

    for pathology in data["pathologies"]:
        value = randint(0, 100)
        data["pathologies"][pathology] = {"presence": value, "absence": 100-value}

    response = {}
    response["filename"] = sys.argv[1]
    response["pathologies"] = {}
    results = sorted(data["pathologies"], key=lambda k: data["pathologies"][k]["presence"], reverse=True)

    for entry in results:
        response["pathologies"][entry] = data["pathologies"][entry]

    del data
    print(json.dumps(response, indent=2))

if __name__ == '__main__':
    main()
