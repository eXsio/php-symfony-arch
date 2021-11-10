import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';

import {environment} from '@environments/environment';
import {Page, PostHeader, Tag} from '@app/_models';

@Injectable({providedIn: 'root'})
export class TagService {
  constructor(private http: HttpClient) {
  }

  getPostsByTag(tag: string, pageNo: number) {
    return this.http.get<Page<PostHeader>>(`${environment.apiUrl}/api/tags/` + tag + '?pageNo=' + pageNo);
  }

  getTags() {
    return this.http.get<Tag[]>(`${environment.apiUrl}/api/tags/`);
  }
}
