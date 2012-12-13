package org.solr;

import org.apache.solr.client.solrj.response.QueryResponse;
import org.apache.solr.common.SolrInputDocument;
import org.apache.solr.common.util.NamedList;

public interface ISolrWrapper 
{
	public NamedList<Object> addDocument(SolrInputDocument document);
	public QueryResponse select(String queryString);
	public NamedList<Object> deleteDocumentById(String id);
}
