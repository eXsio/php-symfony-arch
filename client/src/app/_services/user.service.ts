import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';

import {environment} from '@environments/environment';
import {Page, PostHeader} from '@app/_models';

@Injectable({providedIn: 'root'})
export class UserService {
  constructor(private http: HttpClient) {
  }

  getPostsByUserId(userId: string, pageNo: number) {
    return this.http.get<Page<PostHeader>>(`${environment.apiUrl}/api/security/` + userId + '?pageNo=' + pageNo);
  }
}
