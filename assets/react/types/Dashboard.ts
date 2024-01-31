

export type Sluggable = { slug: string, name: string };

export type SelectedTerritoires = Record<string, string[]>;

export interface Thematique {
    avg_points: string;
    avg_total: string;
    name: string;
    score: string;
    slug: string;
}

export type Thematiques = Thematique[];

export interface TerritoireItem {
    slug: string;
    name:  string;
    numberOfReponses: number;
    score: number;
}

export type TerritoireList = TerritoireItem[]

export interface RepondantItem {
    city: string;
    company: string;
    points: number;
    total: number;
    typologie: string;
    url: string;
    uuid: string;
}

export type RepondantList = RepondantItem[]

export interface Lists {
    departments?: TerritoireList,
    ots?: TerritoireList,
    repondants?: RepondantList,
}